<?php

namespace CodexSoft\Code\Helpers;

use Carbon\Carbon;
use CodexSoft\Code\Constants;
use function CodexSoft\Code\DTZ;

class DateAndTime
{

    /**
     * Сменит часовой пояс в объекте $dateTime без смены даты и времени
     *
     * @param \DateTime $dateTime
     * @param string $targetTimezone
     *
     * @return Carbon
     */
    public static function switchTimezoneSavingDate(\DateTime $dateTime,string $targetTimezone = Constants::TZ_DTZ): Carbon
    {
        $dateTimeIsoString = Carbon::instance($dateTime)->format(\DateTime::ATOM);
        $convertedDateTime = Carbon::createFromFormat(\DateTime::ATOM,$dateTimeIsoString,$targetTimezone);
        return $convertedDateTime;
    }

    public static function getIsoDateString(\DateTime $dateTime): string
    {
        return Carbon::instance($dateTime)->format(Constants::FORMAT_YMD);
    }

    public static function getDtzIsoDateString(\DateTime $dateTime): string
    {
        return Carbon::instance($dateTime)->setTimezone(Constants::TZ_DTZ)->format(Constants::FORMAT_YMD);
    }

    public static function getIsoDateTimeString(\DateTime $dateTime): string
    {
        return Carbon::instance($dateTime)->format(Constants::FORMAT_YMD_HIS);
    }

    public static function getDtzIsoDateTimeString(\DateTime $dateTime): string
    {
        return Carbon::instance($dateTime)->setTimezone(Constants::TZ_DTZ)->format(Constants::FORMAT_YMD_HIS);
    }

    /**
     * Вернет DateTime-границы суток в default часовом поясе (дата вычисляется из часового пояса,
     * указанного в $dateTime)
     *
     * @param \DateTime $dateTime
     * @param string $representedInTimezone
     *
     * @return DoubleDateTime
     */
    public static function getDateBoundsDTZ(\DateTime $dateTime, $representedInTimezone = Constants::TZ_DTZ): DoubleDateTime
    {
        /**
         * иногда нам неизвестно правильно ли выставлен часовой пояс в $dateTime
         */
        //$currentDateIsoString = Carbon::instance($dateTime)->format(Constants::FORMAT_YMD);
        //$utcDate = Carbon::createFromFormat(Constants::FORMAT_YMD,$currentDateIsoString);
        $dtzDate = self::switchTimezoneSavingDate($dateTime);
        return new DoubleDateTime($dtzDate->copy()->startOfDay(), $dtzDate->copy()->endOfDay());
    }

    /**
     * @return DoubleDateTime
     */
    public static function getDateBounds(\DateTime $dateTime, string $representedInTimezone = Constants::TZ_DTZ, string $targetTimezone = Constants::TZ_DTZ): DoubleDateTime
    {

        //$territory = $this->getTerritoryOrFail();
        //$timezone = $territory->getTimezoneWithFallbackToRegionTimezone();


        //Carbon::instance($dateTime)->setTimezone()

        $currentDateIsoString = Carbon::instance($dateTime)->format(Constants::FORMAT_YMD);
        $utcDate = Carbon::createFromFormat(Constants::FORMAT_YMD,$currentDateIsoString);
        return new DoubleDateTime($utcDate->copy()->startOfDay(), $utcDate->copy()->endOfDay());

        //$dateFromDTZ = DTZ($currentDateIsoString.' 00:00:00', $representedInTimezone);
        //$dateTillDTZ = DTZ($currentDateIsoString.' 23:59:59', $representedInTimezone);
        //return new DoubleDateTime($dateFromDTZ, $dateTillDTZ);

    }

    public static function convertUtcDateTimeToLocal(\DateTime $dateTime, string $timezone): \DateTime
    {
        return Carbon::instance($dateTime)->setTimezone($timezone);
    }

    public static function convertUtcDateTimeToLocalString(\DateTime $dateTime, string $timezone): string
    {
        return Carbon::instance($dateTime)->setTimezone($timezone)->format(Constants::FORMAT_YMD_HIS);
    }

    /**
     * @param $dateTimeString
     * @param string $timezone
     * @return string|null
     */
    public static function convertUtcDateTimeStringToLocalString($dateTimeString, string $timezone): ?string
    {
        $dateTime = \DateTime::createFromFormat(Constants::FORMAT_YMD_HIS, $dateTimeString);

        return $dateTime
            ? self::convertUtcDateTimeToLocalString(
                \DateTime::createFromFormat(Constants::FORMAT_YMD_HIS, $dateTimeString),
                $timezone
            )
            : null;
    }

    public static function convertUtcDateTimeToLocalDateString(\DateTime $dateTime, string $timezone): string
    {
        return Carbon::instance($dateTime)->setTimezone($timezone)->format(Constants::FORMAT_YMD);
    }

    public static function convertSeparatedDateAndTimeToDTZ(
        \DateTime $date,
        \DateTime $time,
        string $representedInTimezone
    ): Carbon
    {

        $timeCarbon = Carbon::instance($time);
        $datetimeToStart = Carbon::instance($date)
            ->addHours($timeCarbon->hour)
            ->addMinutes($timeCarbon->minute);

        return DTZ($datetimeToStart->format(Constants::FORMAT_YMD_HIS),$representedInTimezone);
    }

    public static function convertSeparatedStringDateAndTimeToDTZ(
        string $date,
        string $time,
        string $representedInTimezone
    ): Carbon
    {

        $dateTime = "$date $time:00";
        return DTZ($dateTime,$representedInTimezone);
    }

    public static function concatSeparatedDateAndTime(
        \DateTime $date,
        \DateTime $time
    ): Carbon
    {

        $timeCarbon = Carbon::instance($time);
        $datetime = Carbon::instance($date)
            ->addHours($timeCarbon->hour)
            ->addMinutes($timeCarbon->minute);

        return $datetime;
    }

    /**
     * Хэлпер преобразования dateTime в ГОД-МЕСЯЦ-ДЕНЬ, который может быть null. НЕ преобразовывает timezone!!!
     *
     * @param \DateTime|null $dateTime
     *
     * @param null $targetTimezone
     *
     * @return null|string
     */
    public static function ymd(?\DateTime $dateTime, $targetTimezone = null): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        $dt = clone $dateTime;

        if ($targetTimezone) {
            $dt->setTimezone(new \DateTimeZone($targetTimezone));
        }

        return $dt->format(Constants::FORMAT_YMD);
    }

    /**
     * Хэлпер преобразования dateTime в ГОД, который может быть null. НЕ преобразовывает timezone!!!
     *
     * @param \DateTime|null $dateTime
     *
     * @param null $targetTimezone
     *
     * @return null|string
     */
    public static function year(?\DateTime $dateTime, $targetTimezone = null): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        $dt = clone $dateTime;

        if ($targetTimezone) {
            $dt->setTimezone(new \DateTimeZone($targetTimezone));
        }

        return $dt->format(Constants::FORMAT_YEAR);
    }

    /**
     * Хэлпер преобразования dateTime в TIMESTAMP, который может быть null. НЕ преобразовывает timezone!!!
     *
     * @param \DateTime|null $dateTime
     *
     * @return int|null
     */
    public static function timestamp(?\DateTime $dateTime): ?int
    {
        if ($dateTime === null) {
            return null;
        }

        return $dateTime->getTimestamp();
    }

}
