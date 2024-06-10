<?php

namespace App\Service\API;

use App\Repository\ExperienceRepository;
use App\Utils\ServiceTrait;

final class WebsiteService
{
    use ServiceTrait;

    public function __construct(
        private ExperienceRepository $experienceRepository,
    ) {
    }

    public function getData(): object
    {
        return $this->sendJson([
            'experiences' => $this->experienceRepository->findBy(['published' => true]),
        ]);
    }
}
