<?php

namespace App\Service\Admin;

use App\Entity\Experience;
use App\Entity\User;
use App\Repository\ExperienceRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ExperienceService
{

    use ServiceTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private ExperienceRepository $repository,
        private Security $security,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function index(): array
    {
        $breadcrumb = $this->breadcrumb();
        $experiences = $this->repository->findAll();

        return compact('breadcrumb', 'experiences');
    }

    /**
     * @return array
     */
    public function create(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Ajouter une expérience')]);

        return compact('breadcrumb',);
    }

    /**
     * @return array
     */
    public function edit(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Modifier une expérience')]);

        return compact('breadcrumb',);
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

    /**
     * save
     *
     * @param  Experience $experience
     * @return bool
     */
    public function save(Experience $experience): bool
    {
        try {
            $this->manager->persist($experience);
            $this->manager->flush();
            return true;
        } catch (ORMException $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        }
    }

    /**
     * get logged User
     *
     * @return User 
     */
    private function getUser(): ?User
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $user;
        }
        return null;
    }
}
