<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserRequest;
use App\Enum\UserRequestEnum;
use App\Helpers\DateTimeHelperTrait;
use App\Repository\UserRepository;
use App\Repository\UserRequestRepository;
use App\Service\Mail\Auth\ConfirmChangePasswordEmail;
use App\Service\Mail\Auth\ConfirmResetPasswordEmail;
use App\Service\Mail\Auth\SendForgotPasswordEmail;
use App\Service\Token\TokenGenerator;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class AuthService
{

    use ServiceTrait;
    use DateTimeHelperTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $hasher,
        private SerializerInterface $serializer,
        private Security $security,
        private UserRepository $userRepository,
        private UserRequestRepository $userRequestRepository,
        private TokenGenerator $tokenGenerator,
        private SendForgotPasswordEmail $sendForgotPasswordEmail,
        private ConfirmResetPasswordEmail $confirmResetPasswordEmail,
        private ConfirmChangePasswordEmail $confirmChangePasswordEmail
    ) {
    }

    /**
     * @param string|null $email
     * 
     * @return void
     */
    public function forgotPassword(?string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user) {
            $userRequest = new UserRequest;

            $userRequest->setExpiredAt($this->now()->modify('+30 minutes'))
                ->setName(UserRequestEnum::RESET_PASSWORD)
                ->setToken($this->tokenGenerator->generate(60))
                ->setCreatedAt($this->now());

            $user->addRequest($userRequest);

            $bool_save = $this->save($user);
            if ($bool_save) {
                $this->sendForgotPasswordEmail->send($userRequest);
            }
            $this->addFlash('Un email vous a été envoyé pour réinitialiser votre mot de passe.');
        } else {
            $this->addFlash(sprintf('Aucun compte existe pour cet adresse email : %s.', $email), 'danger');
        }
    }

    /**
     * @param Request $request
     * 
     * @return User|null
     */
    public function checkToken(Request $request): UserRequest|array
    {
        $error = [];
        $token =  $request->query->get('t');

        if ($token === null) {
            $this->addFlash('Accès non autorisé !', 'danger');
            $error['state'] = 'none';

            return $error;
        }

        $userRequest = $this->userRequestRepository->findOneBy(compact('token'));

        if ($userRequest instanceof UserRequest) {
            if ($this->isPastDate($userRequest->getExpiredAt())) {
                $this->addFlash('Le lien a expiré !', 'danger');
                $error['state'] = 'expired';

                return $error;
            }

            return $userRequest;
        }

        return $error;
    }

    /**
     * @param string $password
     * @param UserRequest $userRequest
     * 
     * @return bool
     */
    public function resetPassword(string $password, UserRequest $userRequest): bool
    {
        $userRequest->setConsumedAt($this->now());
        $user = $userRequest->getUser();
        $this->hash($user->setPassword($password));
        $bool_save = $this->save($user);
        if ($bool_save) {
            $this->confirmResetPasswordEmail->send($user);
            $this->addFlash('Mot de passe modifié 👍', 'success');
        } else {
            $this->addFlash('Mot de passe non modifié 👎', 'danger');
        }

        return $bool_save;
    }

    /**
     * @param Request $request
     * 
     * @return object
     */
    public function changePassword(Request $request): object
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];
        $constraintsErrors = [];
        $user = $this->getUser();

        if (!array_key_exists('currentPassword', $data) || $data['currentPassword'] === null) {
            $errors['currentPassword'] = 'Champs obligatoire !';
        }

        if (!array_key_exists('newPassword', $data) || $data['newPassword'] === null) {
            $errors['newPassword'] = 'Champs obligatoire !';
        }

        if (!array_key_exists('confirmNewPassword', $data) || $data['confirmNewPassword'] === null) {
            $errors['confirmNewPassword'] = 'Champs obligatoire !';
        }

        if (count($errors) > 0) {
            return $this->sendCustomViolations($errors);
        }

        if (!$this->hasher->isPasswordValid($user, $data['currentPassword'])) {
            $constraintsErrors['currentPassword'] = 'Mot de passe incorrecte';

            return $this->sendCustomViolations($constraintsErrors);
        }

        if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}#', $data['newPassword'])) {
            $constraintsErrors['newPassword'] = 'Le mot de passe doit contenir au moins une masjuscule, une minuscule et un chiffre avec au moins 8 caractères !';

            return $this->sendCustomViolations($constraintsErrors);
        }

        if ($data['newPassword'] !== $data['confirmNewPassword']) {
            $constraintsErrors['confirmNewPassword'] = 'Les deux mots de passe doivent être identiques !';

            return $this->sendCustomViolations($constraintsErrors);
        }

        $this->hash($user->setPassword($data['newPassword']));
        $bool_save = $this->save($user);
        if ($bool_save) {
            $this->confirmChangePasswordEmail->send($user);
            return $this->sendJson(['message' => 'Mot de passe modifié', 'success' => true]);
        }

        return $this->sendJson(['message' => 'Une erreur est survenue lors de la sauvegarde', 'success' => true], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * hash
     *
     * @param  mixed $user
     * @return User
     */
    public function hash(User $user): User
    {
        return $user->setPassword(
            $this->hasher->hashPassword($user, $user->getPassword())
        );
    }

    /**
     * save
     *
     * @param  User $user
     * @return bool
     */
    public function save(User $user): bool
    {
        try {
            $this->manager->persist($user);
            $this->manager->flush();
            return true;
        } catch (ORMException $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        }
    }

    /**
     * get logged User
     *
     * @return User 
     */
    private function getUser(): ?User
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $user;
        }
        return null;
    }
}
