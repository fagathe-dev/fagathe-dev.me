<?php

namespace App\Service\Admin;

use App\Entity\Contact;
use App\Entity\User;
use App\Enum\StateContactEnum;
use App\Helpers\DateTimeHelperTrait;
use App\Repository\ContactRepository;
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

final class ContactService
{

    use ServiceTrait;
    use DateTimeHelperTrait;

    private ?Filesystem $fs = null;

    public function __construct(
        private EntityManagerInterface $manager,
        private Security $security,
        private ContactRepository $repository,
        private UrlGeneratorInterface $urlGenerator,
        private PaginatorInterface $paginator,
        private Uploader $uploader,
    ) {
        $this->fs = new Filesystem;
    }

    /**
     * @return array
     */
    public function index(Request $request, array $criterias = []): array
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
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Ajouter un contact')]);

        return compact('breadcrumb',);
    }

    /**
     * @return array
     */
    public function edit(Contact $contact): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Modifier un contact')]);

        if ($contact->getState() === StateContactEnum::STATE_NON_LU) {
            $this->update($contact->setState(StateContactEnum::STATE_LU));
        }

        return compact('breadcrumb', 'contact',);
    }

    /**
     * @param Request $request
     */
    private function getPagination(Request $request): array
    {
        $data = $this->repository->filter($request->query->all());
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
            new BreadcrumbItem('Liste des contacts', $this->urlGenerator->generate('admin_contact_index')),
            ...$items
        ]);
    }

    /**
     * @param Contact $contact
     * 
     * @return object
     */
    public function delete(Contact $contact): object
    {
        try {
            $this->manager->remove($contact);
            $this->manager->flush();

            return $this->sendNoContent();
        } catch (ORMException $e) {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement du contact !', 'danger');
            return $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return  $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Contact $contact
     * 
     * @return bool
     */
    public function createAction(Contact $contact): bool
    {
        $contact->setCreatedAt($this->now());

        return $this->save($contact);
    }

    /**
     * @param Contact $contact
     * 
     * @return bool
     */
    public function update(Contact $contact): bool
    {
        $contact->setUpdatedAt($this->now());

        return $this->save($contact);
    }

    /**
     * save
     *
     * @param  Contact $contact
     * @return bool
     */
    public function save(Contact $contact): bool
    {
        try {
            $this->manager->persist($contact);
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