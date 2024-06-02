<?php

namespace App\Twig;

use App\Enum\StateContactEnum;
use App\Service\Menu\Menu;
use App\Service\Menu\MenuGenerator;
use App\Service\Menu\MenuItem;
use App\Service\Menu\MenuItemGroup;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class MenuExtension extends AbstractExtension
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generate_menu', [$this, 'generateMenu'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * generateMenu
     *
     * @return string
     */
    public function generateMenu(): string
    {
        return (new MenuGenerator($this->getMenu()))->generate();
    }

    /**
     * @return Menu
     */
    private function getMenu(): Menu
    {
        return (new Menu)
            ->addItem(new MenuItemGroup('Expérience', [
                new MenuItem('Gestions des expériences', $this->urlGenerator->generate('admin_experience_index')),
                new MenuItem('Ajouter une expérience', $this->urlGenerator->generate('admin_experience_create')),
            ]))
            ->addItem(new MenuItemGroup('Skill', [
                new MenuItem('Gestion des skills',  $this->urlGenerator->generate('admin_skill_index')),
                new MenuItem('Ajouter un skill',  $this->urlGenerator->generate('admin_skill_create')),
            ]))
            ->addItem(new MenuItemGroup('Seo', [
                new MenuItem('Gestion du seo', $this->urlGenerator->generate('admin_seo_index')),
                new MenuItem('Ajouter une page', $this->urlGenerator->generate('admin_seo_create')),
                new MenuItem('Gestion des balises', $this->urlGenerator->generate('admin_seo_tags_index')),
                new MenuItem('Ajouter une balise', $this->urlGenerator->generate('admin_seo_tags_create')),
            ]))
            ->addItem(new MenuItemGroup('Projet', [
                new MenuItem('Gestion des projets', $this->urlGenerator->generate('admin_project_index')),
                new MenuItem('Ajouter un projet', $this->urlGenerator->generate('admin_project_create')),
            ]))
            ->addItem(new MenuItemGroup('Contact', [
                new MenuItem('Gestion des contacts',  $this->urlGenerator->generate('admin_contact_index')),
                new MenuItem('Non-lus',  $this->urlGenerator->generate('admin_contact_index', ['state' => StateContactEnum::STATE_NON_LU])),
            ]))
            ->addItem(new MenuItemGroup('Tracking', [
                new MenuItem('Gestion des évènements', '#'),
                new MenuItem('Ajouter un évènement', '#'),
                new MenuItem('Les derniers logs', '#'),
            ]))
            ->addItem(new MenuItem('Emails', $this->urlGenerator->generate('admin_email_index')));
    }
}
