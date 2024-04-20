<?php

namespace App\Twig;

use App\Service\Menu\Menu;
use App\Service\Menu\MenuGenerator;
use App\Service\Menu\MenuItem;
use App\Service\Menu\MenuItemGroup;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class MenuExtension extends AbstractExtension
{
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
                new MenuItem('Toutes les expériences', '#'),
                new MenuItem('Ajouter une expérience', '#'),
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
            ]));
    }
}
