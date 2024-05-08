<?php

namespace App\Controller\Admin;

use App\Service\Admin\SEOService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/seo', name: 'admin_seo_')]
final class SeoController extends AbstractController
{

    public function __construct(
        private SEOService $service,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function indexSEO(): Response
    {
        return $this->render('admin/seo/index.html.twig', $this->service->index());
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function createSEO(): Response
    {
        return $this->render('admin/seo/index.html.twig');
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editSEO(): Response
    {
        return $this->render('admin/seo/index.html.twig');
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteSEO(): Response
    {
        return $this->render('admin/seo/index.html.twig');
    }
}
