<?php 
namespace App\Service\Menu;

use Symfony\Component\HttpFoundation\Request;

final class MenuItem {

    private ?Request $request = null;

    public function __construct(
        private string $label, 
        private string $url, 
        private bool $isActive = false,
    ){
        $this->initRequest();
        $this->setIsActive();
    }


    /**
     * Get the value of url
     */ 
    public function getUrl():string
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */ 
    public function setUrl(string $url):self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of label
     */ 
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get the value of isActive
     */ 
    public function setLabel(bool $label):self
    {
        $this->label = $label;
        
        return $this;
    }

    /**
     * Get the value of isActive
     */ 
    public function isActive():bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive
     *
     * @return  self
     */ 
    public function setIsActive(): self
    {
        $this->isActive = $this->request->getPathInfo() === $this->url;

        return $this;
    }

    /**
     * @return self
     */
    public function initRequest():self 
    {
        $this->request = Request::createFromGlobals();

        return $this;
    }
}