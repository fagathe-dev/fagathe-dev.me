<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\Admin\ProjectType;
use App\Service\Admin\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/project', name: 'admin_project_')]
final class ProjectController extends AbstractController
{

    public function __construct(
        private ProjectService $service
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/project/index.html.twig', $this->service->index($request));
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $project = new Project;
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($project)) {
                $this->addFlash('info', 'Projet créée 👍');

                return $this->redirectToRoute('admin_project_edit', ['id' => $project->getId()]);
            }
        }

        return $this->render('admin/project/create.html.twig', [...compact('form'), ...$this->service->create()]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Project $project, Request $request): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($project)) {
                $this->addFlash('info', 'Projet modifiée 👍');

                return $this->redirectToRoute('admin_project_edit', ['id' => $project->getId()]);
            }
        }
        return $this->render('admin/project/edit.html.twig', [...compact('form'), ...$this->service->edit()]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Project $project): JsonResponse
    {
        $response = $this->service->delete($project);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
        );
    }
}
