<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TypeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('type', [$this, 'getType']),
        ];
    }

    public function getType(mixed $var): string
    {
        return gettype($var);
    }
}
