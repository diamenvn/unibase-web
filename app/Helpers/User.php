<?php

namespace App\Helpers;

class User
{
    public static function isUserMkt($user)
    {
        return $user->type_account == "mkt" && $user->permission == "user";
    }

    public static function isUserSale($user)
    {
        return $user->type_account == "sale" && $user->permission == "user";
    }

    public static function isSale($user)
    {
        return $user->type_account == "sale";
    }

    public static function isAdminSale($user)
    {
        return $user->type_account == "sale" && User::isAdmin($user);
    }

    public static function isAdminMkt($user)
    {
        return $user->type_account == "mkt" && User::isAdmin($user);
    }

    public static function isMkt($user)
    {
        return $user->type_account == "mkt";
    }

    public static function isAdmin($user)
    {
        return $user->permission == "admin" || User::isSuperAdmin($user);
    }

    public static function isVandon($user)
    {
        return $user->type_account == "bill";
    }

    public static function isUser($user)
    {
        return $user->permission == "user";
    }

    public static function isSuperAdmin($user)
    {
        return $user->permission == "super";
    }

    public static function isCare($user)
    {
        return $user->type_account == "care";
    }
}
