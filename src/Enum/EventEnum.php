<?php

namespace App\Enum;

use App\Enum\EnumInterface;

final class EventEnum implements EnumInterface
{

    public const TYPE_CTA = 'TYPE_CTA';
    public const TYPE_SUBMIT_FORM = 'TYPE_SUBMIT_FORM';
    public const TYPE_REDIRECTION = 'TYPE_REDIRECTION';
    public const TYPE_BACKLINK = 'TYPE_BACKLINK';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::TYPE_CTA,
            self::TYPE_SUBMIT_FORM,
            self::TYPE_REDIRECTION,
            self::TYPE_BACKLINK,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::TYPE_CTA): string
    {
        return match ($value) {
            self::TYPE_BACKLINK => 'BACKLINK',
            self::TYPE_SUBMIT_FORM => 'SUBMIT_FORM',
            self::TYPE_REDIRECTION => 'REDIRECTION',
            default => 'CTA',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'CTA' => self::TYPE_CTA,
            'SUBMIT_FORM' => self::TYPE_SUBMIT_FORM,
            'REDIRECTION' => self::TYPE_REDIRECTION,
            'BACKLINK' => self::TYPE_BACKLINK,
        ];
    }
}
