<?php

namespace CodexSoft\Code\Helpers;
use function CodexSoft\Code\str;

class Formatter
{

    /**
     * Форматирует стоимость для отображения.
     *
     * @param $number
     * @param int $decimals
     * @param string $decimalsSep
     * @param string $thousandsSep
     * @return string
     */
    public static function formatPrice($number, $decimals = 2, $decimalsSep = '.', $thousandsSep = ' '): string
    {
        $number = (float) $number;

        if ((round($number) === $number))
        {
            return number_format($number, 0, $decimalsSep, $thousandsSep);
        }

        return number_format($number, $decimals, $decimalsSep, $thousandsSep);
    }

    /**
     * @param string $phone
     * @param string|null $extraNumber
     * @return string
     */
    public static function formatPhone(string $phone, string $extraNumber = null): string
    {
        // Правильный формат номера - (ccc) aaa-bb-cc
        $phone = self::clearPhone($phone);
        $extraNumber = str($extraNumber)->trim();

        $phone = str($phone);

        if ($phone->length() < 10) {
            return false;
        }

        $code = '7';

        if ($phone->length() === 11) {
            $code  = (string) $phone->substr(0, 1);
            $phone = $phone->substr(1);
        }

        $phoneFormatted = '+' . $code . ' ';
        $phoneFormatted.= '(' . $phone->substr(0, 3) . ') ';
        $phoneFormatted.= $phone->substr(3, 3) . '-';
        $phoneFormatted.= $phone->substr(6, 2) . '-';
        $phoneFormatted.= $phone->substr(8, 2);

        if ($extraNumber->length() > 0) {
            $phoneFormatted.= ' доб. ' . $extraNumber;
        }

        return $phoneFormatted;
    }

    /**
     * Очищение номера телефона от лишних символов.
     *
     * @param string $phone
     * @return string
     */
    public static function clearPhone(?string $phone): ?string
    {
        return preg_replace('/\D+/', '', $phone);
    }

}