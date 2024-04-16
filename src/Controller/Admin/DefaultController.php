<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{

    #[Route('/admin', name: 'admin_default', methods: ['GET'])]
    public function index():Response
    {
        return $this->render('admin/layouts.html.twig');
    }

}
