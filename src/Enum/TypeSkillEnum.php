<?php

namespace App\Enum;
use App\Enum\EnumInterface;

final class TypeSkillEnum implements EnumInterface
{

    public const TYPE_TOOLS = 'TYPE_TOOLS';
    public const TYPE_FRONT = 'TYPE_FRONT';
    public const TYPE_BACK = 'TYPE_BACK';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::TYPE_TOOLS,
            self::TYPE_FRONT,
            self::TYPE_BACK,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::TYPE_BACK): string
    {
        return match ($value) {
            self::TYPE_TOOLS => 'Tools',
            self::TYPE_FRONT => 'Front',
            default => 'Back',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Tools' => self::TYPE_TOOLS,
            'Front' => self::TYPE_FRONT,
            'Back' => self::TYPE_BACK,
        ];
    }
}
