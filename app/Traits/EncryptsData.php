<?php

namespace App\Traits;

trait EncryptsData
{
    public static function hashPassword(string $password) : string
    {
        return \Hash::make($password);
    }

    public static function encryptData(string $data) : string
    {
        return encrypt($data);
    }

    public static function decryptData(string $data) : string
    {
        return decrypt($data);
    }
}
