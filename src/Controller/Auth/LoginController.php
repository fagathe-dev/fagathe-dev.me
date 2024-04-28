<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login', name: 'auth_login')]
final class LoginController extends AbstractController
{
    
    
    #[Route('', name: '')]
    public function index():Response 
    {
        return $this->render('auth/security/login.html.twig');
    }

}
