<?php

namespace App\Controller\Auth;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ChangePasswordController extends AbstractController
{

    public function __construct(
        private AuthService $service
    ) {
    }

    #[Route('/auth/api/change-password', name: 'auth_api_change_password', methods: ['POST'])]
    public function changePassword(Request $request): JsonResponse
    {
        $response = $this->service->changePassword($request);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers
        );
    }
}
