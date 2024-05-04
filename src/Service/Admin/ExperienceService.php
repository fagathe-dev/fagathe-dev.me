<?php

namespace App\Service\Admin;

use App\Repository\ExperienceRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ExperienceService
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private ExperienceRepository $repository,
    ) {
    }

    public function index(): array
    {
        $breadcrumb = $this->breadcrumb();
        $experiences = $this->repository->findAll();

        return compact('breadcrumb', 'experiences');
    }

    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function breadcrumb(array $items = []): Breadcrumb
    {
        return new Breadcrumb([
            new BreadcrumbItem('Liste des expériences', $this->urlGenerator->generate('admin_experience_index')),
            ...$items
        ]);
    }
}
