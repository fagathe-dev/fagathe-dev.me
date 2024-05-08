<?php

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Form\Admin\ExperienceType;
use App\Service\Admin\ExperienceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/experience', name: 'admin_experience_')]
final class ExperienceController extends AbstractController
{

    public function __construct(
        private ExperienceService $service,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/experience/index.html.twig', $this->service->index());
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $experience = new Experience;
        $experience->addTask('');

        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($experience)) {
                $this->addFlash('info', 'Expérience créée 🚀');

                $this->redirectToRoute('admin_experience_edit', [
                    'id' => $experience->getId(),
                ]);
            }
        }

        return $this->render('admin/experience/create.html.twig', [...$this->service->create(), 'form' => $form]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Experience $experience, Request $request): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($experience)) {
                $this->addFlash('info', 'Expérience modifiée 🚀');

                $this->redirectToRoute('admin_experience_edit', [
                    'id' => $experience->getId(),
                ]);
            }
        }

        return $this->render('admin/experience/edit.html.twig', [...$this->service->edit(), 'form' => $form]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Experience $experience): JsonResponse
    {
        $response = $this->service->delete($experience);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
        );
    }
}
