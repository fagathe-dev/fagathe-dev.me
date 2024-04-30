<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TypeExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters():array
    {
        return [
            new TwigFilter('type', [$this, 'getType']),
        ];
    }

    /**
     * @param mixed $var
     * 
     * @return string
     */
    public function getType(mixed $var): string
    {
        return gettype($var);
    }
}
