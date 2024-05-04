<?php

namespace App\Enum;

use App\Enum\EnumInterface;

final class EmailTypeEnum implements EnumInterface
{

    public const CONFIRM_CHANGE_PASSWORD = 'CONFIRM_CHANGE_PASSWORD';
    public const RESET_PASSWORD_TOKEN = 'RESET_PASSWORD_TOKEN';
    public const CONFIRM_RESET_EMAIL = 'CONFIRM_RESET_EMAIL';

    /**
     * @return string[]
     */
    public static function cases(): array
    {
        return [
            self::CONFIRM_CHANGE_PASSWORD,
            self::RESET_PASSWORD_TOKEN,
            self::CONFIRM_RESET_EMAIL,
        ];
    }

    /**
     * @param  mixed $value
     * @return string
     */
    public static function match(int|string $value = self::CONFIRM_RESET_EMAIL): string
    {
        return match ($value) {
            self::CONFIRM_CHANGE_PASSWORD => 'Confirmation mot de passe changé',
            self::RESET_PASSWORD_TOKEN => 'Jeton réinitialisation de mot de passe',
            default => 'Confirmation Mot de passe réinitialiser',
        };
    }

    /**
     * @return array
     */
    public static function choices(): array
    {
        return [
            'Confirmation Mot de passe réinitialiser' => self::CONFIRM_RESET_EMAIL,
            'Jeton réinitialisation de mot de passe' => self::RESET_PASSWORD_TOKEN,
            'Confirmation mot de passe changé' => self::CONFIRM_CHANGE_PASSWORD,
        ];
    }

    /**
     * @param  string $value
     * @return array
     */
    public static function get(int|string $value = self::CONFIRM_RESET_EMAIL): ?array
    {
        return match ($value) {
            self::CONFIRM_CHANGE_PASSWORD => [
                'label' => 'Confirmation mot de passe changé',
                'template' => 'emails/forgot-password/confirm-password-change.html.twig',
                'arg' => self::CONFIRM_CHANGE_PASSWORD,
            ],
            self::RESET_PASSWORD_TOKEN => [
                'label' => 'Jeton réinitialisation de mot de passe',
                'template' => 'emails/forgot-password/reset-password-token.html.twig',
                'arg' => self::RESET_PASSWORD_TOKEN,
            ],
            self::CONFIRM_RESET_EMAIL => [
                'label' => 'Confirmation Mot de passe réinitialiser',
                'template' => 'emails/forgot-password/confirm-reset-password.html.twig',
                'arg' => self::CONFIRM_RESET_EMAIL,
            ],

            default => null,
        };
    }

    /**
     * @return array
     */
    public static function emailList(): array
    {
        return [
            self::RESET_PASSWORD_TOKEN => [
                'label' => 'Confirmation mot de passe changé',
                'template' => 'emails/forgot-password/confirm-password-change.html.twig',
                'arg' => self::CONFIRM_CHANGE_PASSWORD,
            ],
            self::CONFIRM_CHANGE_PASSWORD => [
                'label' => 'Jeton réinitialisation de mot de passe',
                'template' => 'emails/forgot-password/reset-password-token.html.twig',
                'arg' => self::RESET_PASSWORD_TOKEN,
            ],
            self::CONFIRM_RESET_EMAIL => [
                'label' => 'Confirmation Mot de passe réinitialiser',
                'template' => 'emails/forgot-password/confirm-reset-password.html.twig',
                'arg' => self::CONFIRM_RESET_EMAIL,
            ],
        ];
    }
}
