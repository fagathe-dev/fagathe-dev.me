<?php

namespace App\Controller\API;

use App\Entity\Experience;
use App\Service\API\WebsiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/website', name: 'api_website_')]
final class WebSiteController extends AbstractController
{

    public function __construct(
        private WebsiteService $service
    )
    {
    }

    #[Route('/xp/{id}', name: 'get_single_xp', methods: ['GET'])]
    public function getSingleXp(Experience $experience): JsonResponse
    {
        return $this->json(
            $experience,
            context: ['groups' => ['read_xp']]
        );
    }

    #[Route('/data', name: 'getData', methods: ['GET'])]
    public function getData(): JsonResponse
    {
        $response = $this->service->getData();
        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
            ['groups' => ['website_data']]
        );
    }
}
