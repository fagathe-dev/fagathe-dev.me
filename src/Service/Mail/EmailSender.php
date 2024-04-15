<?php

namespace App\Service\Mail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class ExampleMail
{

    public const DEFAULT_EMAIL_SENDER = 'noreply@fagathe-dev.me';

    public const CONTACT_EMAIL_SENDER = 'contact@fagathe-dev.me';

    private ?Session $session = null;

    public function __construct(
        private MailerInterface $mailerInterface,
    ) {
        $this->session = new Session;
    }

    public function send(?array $data = []): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from('noreply@fagathe-dev.me')
                ->to(new Address('fagathe77@gmail.com'))
                ->subject('Thanks for signing up!')
                // path of the Twig template to render
                ->htmlTemplate('emails/signup.html.twig')
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
