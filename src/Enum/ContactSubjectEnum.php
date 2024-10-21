<?php

namespace App\Enum;

enum ContactSubjectEnum: string
{

    case JobOffer = 'job_offer';
    case Suggestion = 'suggestion';
    case BugReport = 'bug_report';

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Sélectionner une option' => null,
            'Offre d\'emploi' => static::JobOffer->value,
            'Rapporter une anomalie' => static::BugReport->value,
            'Faire une suggestion' => static::Suggestion->value,
        ];
    }

    /**
     * @param ?string $str
     * 
     * @return null|string
     */
    public static function match(?string $str): ?string
    {
        return match ($str) {
            static::JobOffer->value => 'Offre d\'emploi',
            static::Suggestion->value => 'Suggestion',
            static::BugReport->value => 'Rapporter une anomalie',
            default => '',
        };
    }

    /**
     * @param ?string $str
     * 
     * @return null|string
     */
    public static function reverseMatch(?string $str): ?string
    {
        return match ($str) {
            'Offre d\'emploi' => static::JobOffer->value,
            'Suggestion' => static::Suggestion->value,
            'Rapporter une anomalie' => static::BugReport->value,
            default => 'Sélectionner une option',
        };
    }
}
