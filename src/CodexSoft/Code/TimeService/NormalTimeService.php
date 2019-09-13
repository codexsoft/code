<?php

namespace CodexSoft\Code\TimeService;

use Carbon\Carbon;
use CodexSoft\Code\Constants;

/**
 * Class NormalClockService
 * Default implementation, return real server date/time in default timezone (DTZ).
 */
class NormalTimeService implements TimeServiceInterface
{

    /**
     * @return Carbon|\DateTime
     */
    public function now(): Carbon
    {
        return Carbon::now(new \DateTimeZone(Constants::TZ_DTZ));
    }
}