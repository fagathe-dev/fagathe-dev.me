<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserRequest;
use App\Enum\EmailTypeEnum;
use App\Enum\UserRequestEnum;
use App\Helpers\DateTimeHelperTrait;
use App\Service\Token\TokenGenerator;
use App\Utils\FakerTrait;

final class EmailService
{

    use FakerTrait;
    use DateTimeHelperTrait;


    public function __construct(
        private TokenGenerator $tokenGenerator,
    ) {
    }

    public function index(): array
    {
        $emails = EmailTypeEnum::choices();

        return compact('emails');
    }

    public function show(string $email): ?array
    {
        $dataEmail = EmailTypeEnum::get($email);

        if ($dataEmail === null) {
            return null;
        }

        return [...$dataEmail, 'data' => $this->getData($email)];
    }

    private function getData(string $email): mixed
    {
        $faker = $this->getFakerFactory();
        $data = null;

        if ($email === EmailTypeEnum::CONFIRM_CHANGE_PASSWORD) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setRegisteredAt($this->now());

            $data = $user;
        }

        if ($email === EmailTypeEnum::RESET_PASSWORD_TOKEN) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setRegisteredAt($this->now());


            $userRequest = new UserRequest;

            $userRequest->setExpiredAt($this->now()->modify('+30 minutes'))
                ->setName(UserRequestEnum::RESET_PASSWORD)
                ->setToken($this->tokenGenerator->generate(60))
                ->setCreatedAt($this->now());

            $user->addRequest($userRequest);

            $data = compact('userRequest');
        }

        if ($email === EmailTypeEnum::CONFIRM_RESET_EMAIL) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setRegisteredAt($this->now());

            return compact('user');
        }

        return $data;
    }
}
