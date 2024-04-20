<?php 
namespace App\Service\Menu;

use App\Service\Menu\MenuItem;
use App\Service\Menu\MenuItemGroup;

final class Menu 
{

    public function __construct(
        /**
         * @var MenuItem|MenuItemGroup[] $items
         */
        private array $items = [],
    ) {
    }

    /**
     * @return MenuItem|MenuItemGroup[]
     */
    public function getItems(): array 
    {
        return $this->items;
    }

    /**
     * @param MenuItem|MenuItemGroup $item
     * 
     * @return self
     */
    public function addItem(MenuItem|MenuItemGroup $item):self 
    {
        $this->items = [...$this->items, $item];

        return $this;
    }

    /**
     * @return string
     */
    public function generate(): string 
    {
        return (new MenuGenerator($this))->generate();
    }

}