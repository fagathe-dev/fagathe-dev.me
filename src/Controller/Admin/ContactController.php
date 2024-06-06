<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Enum\StateContactEnum;
use App\Enum\SubjectContactEnum;
use App\Form\Admin\AdminFilterContactType;
use App\Form\Admin\ContactType;
use App\Service\Admin\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact', name: 'admin_contact_')]
final class ContactController extends AbstractController
{

    public function __construct(
        private ContactService $service
    ) {
    }

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $filters = [
            'state' => ['data' => StateContactEnum::choices(), 'label' => 'Statut'],
            'subject' => ['data' => SubjectContactEnum::choices(), 'label' => 'Type']
        ];

        return $this->render('admin/contact/index.html.twig', [...compact('filters'), ...$this->service->index($request)]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Contact $contact, Request $request): Response
    {
        // $form = $this->createForm(AdminFilterContactType::class, $contact);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     if ($this->service->update($contact, $form->get('uploadedImage')->getData())) {
        //         $this->addFlash('info', 'Projet modifiée 👍');

        //         return $this->redirectToRoute('admin_contact_edit', ['id' => $contact->getId()]);
        //     }
        // }
        return $this->render('admin/contact/edit.html.twig', $this->service->edit($contact));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Contact $contact): JsonResponse
    {
        $response = $this->service->delete($contact);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
        );
    }
}
