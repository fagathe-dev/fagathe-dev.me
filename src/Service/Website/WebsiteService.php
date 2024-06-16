<?php

namespace App\Service\Website;

use App\Entity\Skill;
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

    /**
     * @param Skill[] $skills
     * 
     * @return array
     */
    private function filterSkillByType(array $skills = []): array
    {
        $data = [];
        $keys = [];
        foreach ($skills as $d) {
            if (!array_key_exists($d->getNiceType(), $data)) {
                $data[$d->getNiceType()] = []; 
                $keys = [...$keys, $d->getNiceType()];
            }
            array_push($data[$d->getNiceType()], $d);
        }
        return compact('data', 'keys');
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'experiences' => $this->experienceRepository->findBy(['published' => true]),
            'projects' => $this->projectRepository->findLatest(),
            'skills' => $this->filterSkillByType($this->skillRepository->findAll()),
        ];
    }
}
