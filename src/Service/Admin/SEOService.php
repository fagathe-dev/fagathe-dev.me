<?php

namespace App\Service\Admin;

use App\Entity\Seo;
use App\Entity\SeoTag;
use App\Entity\User;
use App\Repository\SeoRepository;
use App\Repository\SeoTagRepository;
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

final class SEOService
{

    use ServiceTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private SeoRepository $seoRepository,
        private SeoTagRepository $seoTagRepository,
        private UrlGeneratorInterface $urlGenerator,
        private Security $security,
        private PaginatorInterface $paginator,
    ) {
    }

    /**
     * @return array
     */
    public function createSEO(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Ajouter une page')]);

        return compact('breadcrumb');
    }

    /**
     * @return array
     */
    public function createSEOTag(Seo $seo): array
    {
        $breadcrumb = $this->breadcrumb([
            new BreadcrumbItem('Page ' . $seo->getName(), $this->urlGenerator->generate('admin_seo_show', ['id' => $seo->getId()])),
            new BreadcrumbItem('Ajouter une balise')
        ]);

        return compact('breadcrumb');
    }

    /**
     * @return array
     */
    public function createTag(): array
    {
        $breadcrumb = $this->breadcrumbTags([
            new BreadcrumbItem('Ajouter une balise')
        ]);

        return compact('breadcrumb');
    }

    /**
     * @return array
     */
    public function editSEO(): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Modifier une page')]);

        return compact('breadcrumb');
    }

    /**
     * @return array
     */
    public function index(): array
    {
        $breadcrumb = $this->breadcrumb();
        $seoPages = $this->seoRepository->findAll();

        return compact('breadcrumb', 'seoPages');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function tagsIndex(Request $request): array
    {
        $breadcrumb = $this->breadcrumbTags();
        $seoTags = $this->getTags($request);

        return compact('breadcrumb', 'seoTags');
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
            new BreadcrumbItem('Gestion du SEO', $this->urlGenerator->generate('admin_seo_index')),
            ...$items
        ]);
    }

    /**
     * breadcrumb
     *
     * @param Breadcrumb[] $items
     * @return Breadcrumb
     */
    public function breadcrumbTags(array $items = []): Breadcrumb
    {
        return new Breadcrumb([
            new BreadcrumbItem('Gestion des balises', $this->urlGenerator->generate('admin_seo_tags_index')),
            ...$items
        ]);
    }

    /**
     * @param Seo $seo
     * 
     * @return object
     */
    public function delete(Seo $seo): object
    {
        try {
            $this->manager->remove($seo);
            $this->manager->flush();

            return $this->sendNoContent();
        } catch (ORMException $e) {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement de la page !', 'danger');
            return $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return  $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SeoTag $tag
     * 
     * @return object
     */
    public function deleteTag(SeoTag $tag): object
    {
        try {
            $this->manager->remove($tag);
            $this->manager->flush();

            return $this->sendNoContent();
        } catch (ORMException $e) {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement de la balise !', 'danger');
            return $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return  $this->sendJson(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Seo $seo
     * 
     * @return array
     */
    public function show(Seo $seo): array
    {
        $breadcrumb = $this->breadcrumb([new BreadcrumbItem('Page ' . $seo->getName())]);

        return compact('breadcrumb', 'seo');
    }

    /**
     * save
     *
     * @param  Seo $seo
     * @return bool
     */
    public function save(Seo $seo): bool
    {
        try {
            $this->manager->persist($seo);
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
     * save
     *
     * @param SeoTag $tag
     * @return bool
     */
    public function saveTag(SeoTag $tag): bool
    {
        try {
            $this->manager->persist($tag);
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
    /**
     * @param  mixed $request
     * @return PaginationInterface
     */
    public function getTags(Request $request): PaginationInterface
    {

        $data = $this->seoTagRepository->findAll();
        $page = $request->query->getInt('page', 1);
        $nbItems = $request->query->getInt('nbItems', 15);

        return $this->paginator->paginate(
            $data,
            /* query NOT result */
            $page,
            /*page number*/
            $nbItems, /*limit per page*/
        );
    }
}
