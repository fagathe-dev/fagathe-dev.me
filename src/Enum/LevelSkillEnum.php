<?php

namespace App\Enum;

use App\Enum\EnumInterface;

final class LevelSkillEnum implements EnumInterface
{

    public const LEVEL_DEBUTANT = 'LEVEL_DEBUTANT';
    public const LEVEL_INTERMEDIAIRE = 'LEVEL_INTERMEDIAIRE';
    public const LEVEL_AVANCE = 'LEVEL_AVANCE';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::LEVEL_DEBUTANT,
            self::LEVEL_INTERMEDIAIRE,
            self::LEVEL_AVANCE,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::LEVEL_AVANCE): string
    {
        return match ($value) {
            self::LEVEL_DEBUTANT => 'Les bases',
            self::LEVEL_INTERMEDIAIRE => 'Intermédiaire',
            default => 'Avancé',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Les bases' => self::LEVEL_DEBUTANT,
            'Intermédiaire' => self::LEVEL_INTERMEDIAIRE,
            'Avancé' => self::LEVEL_AVANCE,
        ];
    }
}
