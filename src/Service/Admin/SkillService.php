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
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
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
        private PaginatorInterface $paginator,
    ) {
    }

    /**
     * @return array
     */
    public function index(Request $request): array
    {
        return [
            'breadcrumb' => $this->breadcrumb(),
            ...$this->getPagination($request),
        ];
    }

    /**
     * @return array
     */
    public function create(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Ajouter une skill')]);

        return compact('breadcrumb',);
    }

    /**
     * @return array
     */
    public function edit(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Modifier une skill')]);

        return compact('breadcrumb',);
    }

    /**
     * @param Request $request
     */
    private function getPagination(Request $request): array
    {

        $data = $this->repository->findAll();
        $page = $request->query->getInt('page', 1);
        $nbItems = $request->query->getInt('nbItems', 10);

        $pagination = $this->paginator->paginate(
            $data,
            /* query NOT result */
            $page,
            /*page number*/
            $nbItems, /*limit per page*/
        );

        $maxPage = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());

        if ($page > $maxPage) {
            $numberCurrentResults = $pagination->getTotalItemCount();
        } else {
            $numberCurrentResults = ($pagination->getCurrentPageNumber() - 1) * $pagination->getItemNumberPerPage() + count($pagination->getItems());
        }

        return compact('pagination', 'numberCurrentResults');
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
