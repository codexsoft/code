<?php

namespace CodexSoft\Code\TimeService;

use Carbon\Carbon;

interface TimeServiceInterface
{

    /**
     * @return Carbon|\DateTime
     */
    public function now(): Carbon;

}