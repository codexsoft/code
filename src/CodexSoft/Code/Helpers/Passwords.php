<?php

namespace CodexSoft\Code\Helpers;

class Passwords
{

    /**
     * @param int $length
     *
     * @return string
     * @throws \Exception
     */
    public static function generatePassword1($length = 32): string
    {

        if( (int) $length <= 8 ){
            $length = 32;
        }

        if (\function_exists('random_bytes')) {
            try {
                return bin2hex( random_bytes( $length ) );
            } catch ( \Exception $e ) {
                if (\function_exists('openssl_random_pseudo_bytes')) {
                    return bin2hex(openssl_random_pseudo_bytes($length));
                }

                return self::generateRandomPassword($length);
            }
        }

        if (\function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }

        return self::generateRandomPassword($length);

    }

    /**
     * @param int $length
     *
     * @return string
     * @throws \Exception
     */
    public static function generatePassword($length = 8): string
    {
        //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ0123456789-_';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = random_int(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    public static function generateCode($length = 6): string
    {
        $min = '1'.str_repeat('0', $length - 1);
        $max = str_repeat('9', $length);

        return mt_rand($min, $max);
    }

    /**
     * @param null $desired_length
     *
     * @return string
     * @throws \Exception
     */
    public static function generateRandomPassword($desired_length = null): string
    {

        $desired_length = $desired_length ?: random_int(8, 12);
        $password = '';

        for($length = 0; $length < $desired_length; $length++) {
            $password .= \chr(random_int(32, 126));
        }

        return str_shuffle($password.microtime());

    }

    public static function hash(string $password): string
    {
        return password_hash($password, \PASSWORD_BCRYPT /* result is always 60 symbols! */);
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

}