<?php

namespace App\Service\API;

use App\Repository\ExperienceRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use App\Utils\ServiceTrait;

final class WebsiteService
{
    use ServiceTrait;

    public function __construct(
        private ExperienceRepository $experienceRepository,
        private ProjectRepository $projectRepository,
        private SkillRepository $skillRepository,
    ) {
    }

    public function getData(): object
    {
        return $this->sendJson([
            'experiences' => $this->experienceRepository->findBy(['published' => true]),
            'projects' => $this->projectRepository->findLatest(),
            'skills' => $this->skillRepository->findAll(),
        ]);
    }
}
