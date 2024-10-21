<?php

namespace App\Controller\Website;

use App\Entity\Contact;
use App\Form\Website\ContactType;
use App\Service\JSON\SEOService;
use App\Service\Website\WebsiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/me-contacter', name: 'website_contact_')]
final class ContactController extends AbstractController
{

    public function __construct(private WebsiteService $service, private SEOService $seoService) {}

    #[Route('', name: 'default', methods: ['GET', 'POST'])]
    public function default(Request $request): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $seo = $this->seoService->getSEO('contactPage');

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->contact($contact);
        }

        return $this->render('website/contact/index.html.twig', compact('seo', 'form'));
    }
}
