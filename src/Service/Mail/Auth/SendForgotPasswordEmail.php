<?php

namespace App\Service\Mail;

use App\Entity\UserRequest;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

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
        try {
            $email = (new TemplatedEmail())
                ->from(self::DEFAULT_EMAIL_SENDER)
                ->to($userRequest->getUser()->getEmail())
                ->subject('Votre mot de passe !')
                // path of the Twig template to render
                ->htmlTemplate('emails/auth/forgot-password.html.twig')
                // pass variables (name => value) to the template
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => 'foo',
                ]);
                
            $this->mailerInterface->send($email);
            return;
        } catch (HttpTransportException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
            return;
        }
    }
}
