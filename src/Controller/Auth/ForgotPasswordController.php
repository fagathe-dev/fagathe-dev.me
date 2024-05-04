<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Entity\UserRequest;
use App\Form\Auth\ForgotPasswordType;
use App\Form\Auth\ResetPasswordType;
use App\Service\AuthService;
use App\Service\Token\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/auth', name: 'auth_')]
final class ForgotPasswordController extends AbstractController
{

    public function __construct(
        private AuthService $service,
        private TokenGenerator $tokenGenerator,
        private UrlGeneratorInterface $urlGeneratorInterface,
        private EntityManagerInterface $manager,
    ) {
    }

    #[Route('/forgot-password', name: 'forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->forgotPassword($form->get('email')->getData());
        }

        return $this->render('auth/forgot-password.html.twig', compact('form'));
    }

    #[Route('/reset-password', name: 'reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        $userRequest = $this->service->checkToken($request);
        $tokenValid = $userRequest instanceof UserRequest;

        if ($userRequest instanceof UserRequest) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->service->resetPassword($form->get('password')->getData(), $userRequest);
            }

            return $this->render('auth/reset-password.html.twig', compact('form', 'tokenValid'));
        }

        return $this->render('auth/reset-password.html.twig', compact('form', 'tokenValid'));
    }
}
