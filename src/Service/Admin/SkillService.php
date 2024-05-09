<?php

namespace App\Service\Admin;

use App\Entity\Skill;
use App\Entity\User;
use App\Repository\SkillRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SkillService
{

    use ServiceTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private Security $security,
        private SkillRepository $repository,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    /**
     * @return array
     */
    public function index(): array
    {
        $breadcrumb = $this->breadcrumb();
        $skills = $this->repository->findAll();

        return compact('skills', 'breadcrumb');
    }

    /**
     * breadcrumb
     *
     * @param Breadcrumb[] $items
     * @return Breadcrumb
     */
    public function breadcrumb(array $items = []): Breadcrumb
    {
        return new Breadcrumb([
            new BreadcrumbItem('Liste des skills', $this->urlGenerator->generate('admin_skill_index')),
            ...$items
        ]);
    }

    /**
     * @param Skill $skill
     * 
     * @return object
     */
    public function delete(Skill $skill): object
    {
        try {
            $this->manager->remove($skill);
            $this->manager->flush();

            return $this->sendNoContent();
        } catch (ORMException $e) {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement de la skill !', 'danger');
            return $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return  $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * save
     *
     * @param  Skill $skill
     * @return bool
     */
    public function save(Skill $skill): bool
    {
        try {
            $this->manager->persist($skill);
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
