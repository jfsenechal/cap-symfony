<?php

namespace Cap\Commercio\Helper;

class DateHelper
{
    public static function formatDateTime(
        \DateTimeInterface $date
    ): string {
        return
            $date->format('Y-m-d');
    }

}