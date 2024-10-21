<?php

namespace App\Service\Website;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Fagathe\Framework\Database\Model\JSON\DataService;

final class WebsiteService
{

    private DataService $skillDataService;
    private DataService $projectDataService;
    private DataService $experienceDataService;

    public function __construct(private ContactRepository $contactRepository, private EntityManagerInterface $manager)
    {
        $this->skillDataService = new DataService('competences');
        $this->projectDataService = new DataService('projets');
        $this->experienceDataService = new DataService('experiences');
    }

    /**
     * @param Contact $contact
     * 
     * @return void
     */
    public function contact(Contact $contact): void
    {
        $contact->setCreatedAt(new DateTimeImmutable);

        $this->manager->persist($contact);
    }

    /**
     * @param array $rawData
     * 
     * @return array
     */
    private function filterDataByType(array $data = []): array
    {
        $keys = [];
        foreach ($data as $d) {
            if (!in_array($d['type'], $keys)) {
                $keys = [...$keys, $d['type']];
            }
        }
        return compact('data', 'keys');
    }

    /**
     * @param array $skills
     * 
     * @return array
     */
    private function filterSkillByType(array $skills = []): array
    {
        $data = [];
        $keys = [];
        foreach ($skills as $d) {
            if (!array_key_exists($d['type'], $data)) {
                $data[$d['type']] = [];
                $keys = [...$keys, $d['type']];
            }
            array_push($data[$d['type']], $d);
        }

        return compact('data', 'keys');
    }

    /**
     * @return array
     */
    public function findProjetById(int $id): array
    {
        return $this->projectDataService->find($id);
    }

    /**
     * @return array
     */
    public function findExperienceById(int $id): array
    {
        return $this->experienceDataService->find($id);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'skills' => $this->filterSkillByType($this->skillDataService->findAll()),
            'experiences' => $this->filterDataByType($this->experienceDataService->findAll()),
            'projects' => $this->filterDataByType($this->projectDataService->findAll()),
        ];
    }
}
