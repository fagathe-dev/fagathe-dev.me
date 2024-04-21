<?php

namespace App\Enum\User;

use App\Entity\User;
use App\Enum\EnumInterface;

final class TypeExperienceEnum implements EnumInterface
{

    public const TYPE_EMPLOI = 'TYPE_EMPLOI';
    public const TYPE_FORMATION = 'TYPE_FORMATION';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::TYPE_FORMATION,
            self::TYPE_EMPLOI,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::TYPE_EMPLOI): string
    {
        return match ($value) {
            self::TYPE_FORMATION => 'Formation',
            default => 'Emploi',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Formation' => self::TYPE_FORMATION,
            'Emploi' => self::TYPE_EMPLOI,
        ];
    }
}
