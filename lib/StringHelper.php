<?php

declare(strict_types=1);

use Random\RandomException;

if (!function_exists('generateRandomString')) {
    /**
     * @throws RandomException
     */
    function generateRandomString(int $length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
