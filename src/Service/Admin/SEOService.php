<?php

namespace App\Service\Admin;

use App\Entity\Seo;
use App\Entity\User;
use App\Repository\SeoRepository;
use App\Repository\SeoTagRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
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
