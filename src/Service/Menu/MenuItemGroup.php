<?php 
namespace App\Service\Menu;

final class MenuItemGroup {

    public function __construct(
        private string $label,
        /**
         * @var MenuItem[] $items
         */ 
        private array $items,
        private bool $expanded = false,
    ) {
        $this->hasExpanded();
    }

    /**
     * @return bool
     */
    private function hasExpanded(): void 
    {
        foreach ($this->items as $item) {
            if ($item->isActive() === true) {
                $this->expanded = true;

                return;
            }
        }

        return;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return MenuItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(MenuItem $item): self 
    {
        $this->items[] = [...$this->items, $item];
        
        return $this;
    }

    /**
     * @return bool
     */
    public function getExpanded(): bool
    {
        foreach ($this->items as $item) {
            if ($item->isActive() === true) {
                $this->expanded = true;

                return $this->expanded;
            }
        }
        
        return false;
    }

}