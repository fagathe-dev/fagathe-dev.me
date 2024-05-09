<?php

namespace App\Controller\Admin;

use App\Entity\Seo;
use App\Entity\SeoTag;
use App\Form\Admin\Seo\PageType;
use App\Form\Admin\Seo\SeoTagType;
use App\Form\Admin\Seo\TagType;
use App\Service\Admin\SEOService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function createSEO(Request $request): Response
    {
        $seo = new Seo;
        $form = $this->createForm(PageType::class, $seo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($seo)) {
                $this->addFlash('info', 'Page créée 🚀');

                return $this->redirectToRoute('admin_seo_edit', [
                    'id' => $seo->getId(),
                ]);
            }
        }

        return $this->render('admin/seo/create.html.twig', array_merge(compact('form'), $this->service->createSEO()));
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editSEO(Seo $seo, Request $request): Response
    {
        $form = $this->createForm(PageType::class, $seo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($seo)) {
                $this->addFlash('info', 'Page modifiée 🚀');
            }
        }

        return $this->render('admin/seo/edit.html.twig', array_merge(compact('form'), $this->service->createSEO()));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteSEO(Seo $seo): JsonResponse
    {
        $response = $this->service->delete($seo);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function showSEO(Seo $seo): Response
    {
        return $this->render('admin/seo/show.html.twig', $this->service->show($seo));
    }

    #[Route('/tag/{id}/create', name: 'seotag_create', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function createSEOTag(Seo $seo, Request $request): Response
    {
        $tag = new SeoTag;
        $form = $this->createForm(SeoTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->saveTag($tag->setSeo($seo))) {
                $this->addFlash('info', 'Tag créée 🚀');

                return $this->redirectToRoute('admin_seo_seotag_edit', [
                    'id' => $tag->getId(),
                ]);
            }
        }

        return $this->render('admin/seo/create_tag.html.twig', array_merge(compact('form', 'seo'), $this->service->createSEOTag($seo)));
    }

    #[Route('/tag/create', name: 'tags_create', methods: ['GET', 'POST'])]
    public function createTag(Request $request): Response
    {
        $tag = new SeoTag;
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->saveTag($tag)) {
                $this->addFlash('info', 'Tag créée 🚀');

                return $this->redirectToRoute('admin_seo_tag_edit', [
                    'id' => $tag->getId(),
                ]);
            }
        }

        return $this->render('admin/seo/create.html.twig', array_merge(compact('form'), $this->service->createTag()));
    }

    #[Route('/seotag/{id}/edit', name: 'seotag_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editSEOTag(SeoTag $tag, Request $request): Response
    {
        $form = $this->createForm(SeoTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->saveTag($tag)) {
                $this->addFlash('info', 'Page modifiée 🚀');
            }
        }

        return $this->render('admin/seo/edit.html.twig', array_merge(compact('form'), $this->service->createSEO()));
    }

    #[Route('/tags', name: 'tags_index', methods: ['GET'])]
    public function tagsIndex(Request $request): Response
    {
        return $this->render('admin/seo/tag/index.html.twig', $this->service->tagsIndex($request));
    }

    #[Route('/tags/{id}', name: 'tag_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteSEOTag(SeoTag $tag): JsonResponse
    {
        $response = $this->service->deleteTag($tag);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
        );
    }

    #[Route('/tag/{id}/edit', name: 'tags_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editTag(SeoTag $tag, Request $request): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->saveTag($tag)) {
                $this->addFlash('info', 'Page modifiée 🚀');
            }
        }

        return $this->render('admin/seo/edit.html.twig', array_merge(compact('form'), $this->service->createSEO()));
    }
}
