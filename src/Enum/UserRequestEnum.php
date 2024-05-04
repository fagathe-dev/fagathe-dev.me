<?php

namespace App\Enum;
use App\Enum\EnumInterface;

final class UserRequestEnum implements EnumInterface
{

    public const RESET_PASSWORD = 'RESET_PASSWORD';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::RESET_PASSWORD,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::RESET_PASSWORD): string
    {
        return match ($value) {
            default => 'RESET_PASSWORD',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'RESET_PASSWORD' => self::RESET_PASSWORD,
        ];
    }
}
