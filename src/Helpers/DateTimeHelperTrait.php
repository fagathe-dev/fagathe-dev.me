<?php

namespace App\Helpers;

use DateTimeImmutable;

trait DateTimeHelperTrait
{
    /**
     * now
     *
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable;
    }

    /**
     * @param string string
     * @param string $to
     * 
     * @return array
     */
    public function years(?string $from = '-20 years', ?string $to = 'now'): array
    {
        $tabYears = [];

        $start = new DateTimeImmutable($from);
        $start = (int) $start->format('Y');
        $end = new DateTimeImmutable($to);
        $end = (int) $end->format('Y');

        for ($i = $start; $i <= $end; $i++) {
            $tabYears = [...$tabYears, '' . $i . ''];
        }

        return $tabYears;
    }
}
