<?php

namespace App\Service\Menu;

use App\Helpers\StringHelperTrait;
use App\Service\Menu\Menu;

final class MenuGenerator
{

    use StringHelperTrait;

    public function __construct(
        private Menu $menu,
    ) {
    }

    private function start(): string
    {
        return '<ul class="list-unstyled ps-0">';
    }

    private function end(): string
    {
        return '</ul>';
    }

    private function itemGroupStart(MenuItemGroup $item): string
    {
        return '<button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#' . $this->sanitizeName($item->getLabel()) . '-collapse" aria-expanded="' . ($item->getExpanded() ? 'true' : 'false') . '">' . $item->getLabel() . '</button>';
    }

    private function itemGroup(MenuItemGroup $itemGroup): string
    {
        $html = $this->itemGroupStart($itemGroup);
        $html .= '<div class="collapse' . ($itemGroup->getExpanded() ? ' show' : '') . '" id="' . $this->sanitizeName($itemGroup->getLabel()) . '-collapse"><ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">';

        foreach ($itemGroup->getItems() as $item) {
            $active = $item->isActive() ? ' active' : '';
            $html .= '<li><a href="' . $item->getUrl() . '" class="link-body-emphasis d-inline-flex text-decoration-none rounded' . $active . '">' . $item->getLabel() . '</a></li>';
        }

        $html .= '</ul></div>';

        return $html;
    }

    private function sanitizeName(string $name):string 
    {
        return strtolower($this->skipAccents($name));
    }

    private function itemSingle(MenuItem $item): string
    {
        $active = $item->isActive() ? ' active' : '';
        return '<a href="' . $item->getUrl() . '" class="btn btn-toggle align-items-center rounded' . $active . '">' . $item->getLabel() . '</a>';
    }

    private function itemStart(): string
    {
        return '<li class="mb-1">';
    }

    private function itemEnd(): string
    {
        return '</li>';
    }

    private function item(MenuItem|MenuItemGroup $item): string
    {
        $html = $this->itemStart();
        if ($item instanceof MenuItem) {
            $html .= $this->itemSingle($item);
        }

        if ($item instanceof MenuItemGroup) {
            $html .= $this->itemGroup($item);
        }
        $html .= $this->itemEnd();

        return $html;
    }

    public function generate(): string
    {
        $html = $this->start();

        foreach ($this->menu->getItems() as $item) {
            $html .= $this->item($item);
        }

        $html .= $this->end();

        return $html;
    }
}
