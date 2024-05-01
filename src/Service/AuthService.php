<?php

namespace App\Service;

use App\Entity\User;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class AuthService
{

    use ServiceTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $hasher,
        private SerializerInterface $serializer,
        private Security $security,
    ) {
    }

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
        $this->save($user);

        return $this->sendJson(['message' => 'Mot de passe modifié', 'success' => true]);
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
