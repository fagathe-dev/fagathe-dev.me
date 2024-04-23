<?php

namespace App\Enum;

use App\Enum\EnumInterface;

final class SubjectContactEnum implements EnumInterface
{

    public const SUBJECT_OFFRE_EMPLOI = 'SUBJECT_OFFRE_EMPLOI';
    public const SUBJECT_SUGGESTION_AMELIORATION = 'SUBJECT_SUGGESTION_AMELIORATION';
    public const SUBJECT_BUG_CONSTATE = 'SUBJECT_BUG_CONSTATE';
    public const SUBJECT_AUTRE = 'SUBJECT_AUTRE';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::SUBJECT_OFFRE_EMPLOI,
            self::SUBJECT_SUGGESTION_AMELIORATION,
            self::SUBJECT_BUG_CONSTATE,
            self::SUBJECT_AUTRE,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::SUBJECT_SUGGESTION_AMELIORATION): string
    {
        return match ($value) {
            self::SUBJECT_OFFRE_EMPLOI => 'Offre d\'emploi',
            self::SUBJECT_BUG_CONSTATE => 'Bug',
            self::SUBJECT_SUGGESTION_AMELIORATION => 'Amélioration',
            default => 'Autre',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'J\'ai une offre d\'emploi' => self::SUBJECT_OFFRE_EMPLOI,
            'J\'ai une suggestion d\'amélioration' => self::SUBJECT_SUGGESTION_AMELIORATION,
            'J\'ai constaté un bug' => self::SUBJECT_BUG_CONSTATE,
            'Autre' => self::SUBJECT_AUTRE,
        ];
    }
}
