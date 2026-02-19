<?php

namespace App\Enums;

enum RoleEnum: string
{
    case USER = 'user';
    case EDITOR = 'editor';
    case ADMIN = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            'user' => 'Usuario',
            'editor' => 'Editor',
            'admin' => 'Administrador',
        ];
    }
}