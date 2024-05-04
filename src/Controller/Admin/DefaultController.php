<?php

namespace App\Controller\Admin;

use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Service\Menu\Menu;
use App\Service\Menu\MenuGenerator;
use App\Service\Menu\MenuItem;
use App\Service\Menu\MenuItemGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{

    #[Route('/admin', name: 'admin_default', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/layout.html.twig');
    }
}
