<?php
namespace App\Controller\Website;

use App\Service\JSON\SEOService;
use App\Service\Website\WebsiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'website_home_')]
final class WebsiteController extends AbstractController
{
    private WebsiteService $service;
    private SEOService $seoService;

    public function __construct()
    {
        $this->service = new WebsiteService;
        $this->seoService = new SEOService;
    }

    #[Route('', name: 'default', methods: ['GET'])]
    public function default(): Response
    {
        return $this->render('website/home/index.html.twig', [...$this->service->getData(), 'seo' => $this->seoService->getSEO('homePage')]);
    }
}
