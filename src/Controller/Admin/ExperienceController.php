<?php

namespace App\Controller\Admin;

use App\Service\Admin\ExperienceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function create(): Response
    {
        return $this->render('admin/experience/index.html.twig');
    }
}
