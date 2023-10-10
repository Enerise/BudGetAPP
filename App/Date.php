<?php

namespace App;

class Date
{
    public static function getTheCurrentDate()
    {
        $currentDate = time();
        return $currentDate = date('Y-m-d', $currentDate);
    }
}
