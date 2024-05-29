<?php

namespace App\Service\Admin;

use App\Entity\Project;
use App\Entity\User;
use App\Helpers\DateTimeHelperTrait;
use App\Repository\ProjectRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Service\Uploader\Uploader;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProjectService
{

    use ServiceTrait;
    use DateTimeHelperTrait;

    private ?Filesystem $fs = null;

    public function __construct(
        private EntityManagerInterface $manager,
        private Security $security,
        private ProjectRepository $repository,
        private UrlGeneratorInterface $urlGenerator,
        private PaginatorInterface $paginator,
        private Uploader $uploader,
    ) {
        $this->fs = new Filesystem;
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
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Ajouter un projet')]);

        return compact('breadcrumb',);
    }

    /**
     * @return array
     */
    public function edit(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Modifier un projet')]);

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
            new BreadcrumbItem('Liste des projets', $this->urlGenerator->generate('admin_project_index')),
            ...$items
        ]);
    }

    /**
     * @param Project $project
     * 
     * @return object
     */
    public function delete(Project $project): object
    {
        try {
            $this->manager->remove($project);
            $this->manager->flush();

            return $this->sendNoContent();
        } catch (ORMException $e) {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement du projet !', 'danger');
            return $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return  $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Project $project
     * @param UploadedFile|null $uploadedFile
     * 
     * @return bool
     */
    public function createAction(Project $project, ?UploadedFile $uploadedFile = null): bool
    {
        $project->setCreatedAt($this->now());

        if ($uploadedFile instanceof UploadedFile) {
            $image = $this->uploader->upload($uploadedFile, ['uploadDir' => 'project', 'type' => 'image']);
            $project->setImage($image->getUploadedFilePath());
        }

        return $this->save($project);
    }

    /**
     * @param Project $project
     * 
     * @return void
     */
    private function deleteImage(Project $project): void
    {
        if ($project->getImage() !== null) {
            $this->fs->remove(ROOT_DIR . $project->getImage());
        }
    }

    /**
     * @param Project $project
     * @param UploadedFile|null $uploadedFile
     * 
     * @return bool
     */
    public function update(Project $project, ?UploadedFile $uploadedFile = null): bool
    {
        $project->setUpdatedAt($this->now());

        if ($uploadedFile instanceof UploadedFile) {
            $image = $this->uploader->upload($uploadedFile, ['uploadDir' => 'project', 'type' => 'image']);
            $this->deleteImage($project);
            $project->setImage($image->getUploadedFilePath());
        }

        return $this->save($project);
    }

    /**
     * save
     *
     * @param  Project $project
     * @return bool
     */
    public function save(Project $project): bool
    {
        try {
            $this->manager->persist($project);
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
