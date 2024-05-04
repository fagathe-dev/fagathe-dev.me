<?php

namespace App\Service\Mail\Auth;

use App\Entity\UserRequest;
use App\Enum\EmailTypeEnum;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\MailerInterface;

final class SendForgotPasswordEmail
{

    public const DEFAULT_EMAIL_SENDER = 'noreply@fagathe-dev.me';

    private ?Session $session = null;

    public function __construct(
        private MailerInterface $mailerInterface,
    ) {
        $this->session = new Session;
    }

    public function send(UserRequest $userRequest): void
    {

        $data = EmailTypeEnum::get(EmailTypeEnum::RESET_PASSWORD_TOKEN);

        try {
            $email = (new TemplatedEmail())
                ->from(self::DEFAULT_EMAIL_SENDER)
                ->to($userRequest->getUser()->getEmail())
                ->subject($data['label'])
                // path of the Twig template to render
                ->htmlTemplate($data['template'])
                // pass variables (name => value) to the template
                ->context([
                    'username' => $userRequest->getUser()->getUsername(),
                    'token' => $userRequest->getToken(),
                ]);

            $this->mailerInterface->send($email);
            return;
        } catch (HttpTransportException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
            return;
        }
    }
}
