<?php

namespace App\Controller\Admin;

use App\Service\Admin\SkillService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/skill', name: 'admin_skill_')]
final class SkillController extends AbstractController
{

    public function __construct(
        private SkillService $service
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/skill/index.html.twig', $this->service->index());
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(): Response
    {
        return $this->render('admin/skill/index.html.twig');
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(): Response
    {
        return $this->render('admin/skill/index.html.twig');
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(): Response
    {
        return $this->render('');
    }
}
