<?php

namespace CodexSoft\Code;

class Constants
{

    // форматы дат/времени

    public const FORMAT_YMD_HIS = 'Y-m-d H:i:s';
    public const FORMAT_YMD = 'Y-m-d';
    public const FORMAT_HOURMIN = 'H:i';
    public const FORMAT_YEAR = 'Y';

    // timezones
    // https://ru.wikipedia.org/wiki/%D0%92%D1%80%D0%B5%D0%BC%D1%8F_%D0%B2_%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D0%B8

    public const TZ_UTC = 'UTC';
    public const TZ_DTZ = self::TZ_UTC; // default time zone

}
