<?php

namespace App\Service\Mail\Auth;

use App\Entity\User;
use App\Enum\EmailTypeEnum;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\MailerInterface;

final class ConfirmChangePasswordEmail
{

    public const DEFAULT_EMAIL_SENDER = 'noreply@fagathe-dev.me';

    private ?Session $session = null;

    public function __construct(
        private MailerInterface $mailerInterface,
    ) {
        $this->session = new Session;
    }

    public function send(User $user): void
    {

        $data = EmailTypeEnum::get(EmailTypeEnum::CONFIRM_CHANGE_PASSWORD);

        try {
            $email = (new TemplatedEmail())
                ->from(self::DEFAULT_EMAIL_SENDER)
                ->to($user->getEmail())
                ->subject($data['label'])
                // path of the Twig template to render
                ->htmlTemplate($data['template'])
                // pass variables (name => value) to the template
                ->context([
                    'user' => $user,
                ]);

            $this->mailerInterface->send($email);
            return;
        } catch (HttpTransportException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
            return;
        }
    }
}
