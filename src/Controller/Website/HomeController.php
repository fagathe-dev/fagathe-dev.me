<?php

namespace App\Controller\Website;

use App\Service\Website\WebsiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'website_home_')]
final class HomeController extends AbstractController
{

    public function __construct(private WebsiteService $websiteService)
    {
        
    }

    #[Route('', name: 'index', methods: ['GET'])]
    #[Route('/home', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('website/home/index.html.twig', $this->websiteService->getData());
    }
}
