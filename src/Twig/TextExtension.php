<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('slice_text', [$this, 'sliceText']),
        ];
    }

    /**
     * @param mixed $var
     * 
     * @return string
     */
    public function sliceText(string $str, int $nbWords = 20): string
    {
        $str = explode(' ', $str);
        return join(' ', array_slice($str, 0, $nbWords)) . (count($str) <= $nbWords ? '' : ' ...');
    }
}
