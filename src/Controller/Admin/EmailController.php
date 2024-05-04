<?php

namespace App\Controller\Admin;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/email', name: 'admin_email_')]
final class EmailController extends AbstractController
{

    public function __construct(
        private EmailService $service
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/emails/index.html.twig', $this->service->index());
    }

    #[Route('/{email}', name: 'show')]
    public function show(string $email): Response
    {
        $response = $this->service->show($email);

        if (!is_array($response)) {
            $this->addFlash('danger', sprintf('Le mail \'%s\' est introuvable.', $email));

            return $this->redirectToRoute('admin_email_index');
        }

        return $this->render($response['template'], $response['data']);
    }
}
