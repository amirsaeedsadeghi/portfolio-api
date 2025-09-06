<?php

namespace App\Enums;

enum UserRoleEnum:string{
    case ADMIN = 'admin';
    case GUEST = 'guest';
    case AUTHOR = 'author';

    public static function values() : array {
        return array_column(self::cases(),'value');
    }
}
