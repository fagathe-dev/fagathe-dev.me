<?php

namespace App\Twig;

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
                new MenuItem('Toutes les expériences', $this->urlGenerator->generate('admin_experience_index')),
                new MenuItem('Ajouter une expérience', $this->urlGenerator->generate('admin_experience_create')),
            ]))
            ->addItem(new MenuItemGroup('Skill', [
                new MenuItem('Tous les skills', '#'),
                new MenuItem('Ajouter un skill', '#'),
            ]))
            ->addItem(new MenuItemGroup('Seo', [
                new MenuItem('Toutes les pages', '#'),
                new MenuItem('Ajouter une page', '#'),
                new MenuItem('Toutes les balises', '#'),
                new MenuItem('Ajouter une balise', '#'),
            ]))
            ->addItem(new MenuItemGroup('Projet', [
                new MenuItem('Tous les projets', '#'),
                new MenuItem('Ajouter un projet', '#'),
                new MenuItem('Toutes les balises', '#'),
                new MenuItem('Ajouter une balise', '#'),
            ]))
            ->addItem(new MenuItemGroup('Contact', [
                new MenuItem('Tous les contacts', '#'),
                new MenuItem('Non-lus', '#'),
            ]))
            ->addItem(new MenuItemGroup('Tracking', [
                new MenuItem('Tous les évènements', '#'),
                new MenuItem('Ajouter un évènement', '#'),
                new MenuItem('Les derniers logs', '#'),
            ]))
            ->addItem(new MenuItem('Emails', $this->urlGenerator->generate('admin_email_index')));
    }
}
