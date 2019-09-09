<?php

namespace CodexSoft\Code;

use Carbon\Carbon;
use Stringy\Stringy;

class Shortcuts
{

    public static function register(): void
    {
        // do nothing but inits class loading with functions
    }

}

const TAB = '    ';

/**
 * Just a shortcut for stringy
 * usage: (string) \App\str('hello')->toUpperCase()
 * @param $str
 *
 * @return Stringy
 */
function str($str): Stringy
{
    return new Stringy($str, 'utf-8');
}

/**
 * convert to Default Time Zone
 * creates Carbon instance and converts it to default timezone
 *
 * @param string $datetime date in ISO format Y-m-d H:i:s
 * @param string $representedInTimezone
 * @example DTZ('2018-04-27 10:10:22', 'UTC+7') -> Carbon 2018-04-27 03:10:22 (UTC)
 *
 * @return Carbon
 */
function DTZ(string $datetime, $representedInTimezone = Constants::TZ_DTZ): Carbon
{
    $normalizedDateTime = Carbon::createFromFormat(Constants::FORMAT_YMD_HIS, $datetime, $representedInTimezone);
    $normalizedDateTime = $normalizedDateTime->setTimezone(Constants::TZ_DTZ);
    return $normalizedDateTime;
}

/**
 * creates Carbon instance and converts it to default timezone
 *
 * @param string $date date in ISO format Y-m-d
 * @example DZ('2018-04-27') -> Carbon 2018-04-27 (UTC)
 *
 * @return Carbon
 */
function DZ(string $date): Carbon
{
    $normalizedDateTime = Carbon::createFromFormat(Constants::FORMAT_YMD, $date, Constants::TZ_DTZ);
    return $normalizedDateTime;
}