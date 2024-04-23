<?php

namespace App\Enum;

use App\Enum\EnumInterface;

final class StateContactEnum implements EnumInterface
{

    public const STATE_LU = 'STATE_LU';
    public const STATE_NON_LU = 'STATE_NON_LU';
    public const STATE_TRAITE = 'STATE_TRAITE';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::STATE_LU,
            self::STATE_NON_LU,
            self::STATE_TRAITE,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::STATE_NON_LU): string
    {
        return match ($value) {
            self::STATE_LU => 'Lu',
            self::STATE_TRAITE => 'Traité',
            default => 'Non-lu',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Lu' => self::STATE_LU,
            'Non-lu' => self::STATE_NON_LU,
            'Traité' => self::STATE_TRAITE,
        ];
    }
}
