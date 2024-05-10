<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Form\Admin\SkillType;
use App\Service\Admin\SkillService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/skill', name: 'admin_skill_')]
final class SkillController extends AbstractController
{

    public function __construct(
        private SkillService $service
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/skill/index.html.twig', $this->service->index($request));
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $skill = new Skill;
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($skill)) {
                $this->addFlash('info', 'Skill créée 👍');

                return $this->redirectToRoute('admin_skill_edit', ['id' => $skill->getId()]);
            }
        }

        return $this->render('admin/skill/create.html.twig', [...compact('form'), ...$this->service->create()]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Skill $skill, Request $request): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->save($skill)) {
                $this->addFlash('info', 'Skill modifiée 👍');

                return $this->redirectToRoute('admin_skill_edit', ['id' => $skill->getId()]);
            }
        }
        return $this->render('admin/skill/edit.html.twig', [...compact('form'), ...$this->service->edit()]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(): Response
    {
        return $this->render('');
    }
}
