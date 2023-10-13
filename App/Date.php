<?php

namespace App;

class Date
{
    public static function getTheCurrentDate()
    {
        $currentDate = time();
        return $currentDate = date('Y-m-d', $currentDate);
    }

    public static function getTheFirstDayOfCurrentMonth()
    {
        return date('Y-m-01');
        //echo date("Y-n-j", strtotime("first day of previous month"));
        //echo date("Y-n-j", strtotime("last day of previous month"));
    }

    public static function getTheLastDayOfCurrentMonth()
    {
        return date('Y-m-t');
        //echo date("Y-n-j", strtotime("first day of previous month"));
        //echo date("Y-n-j", strtotime("last day of previous month"));
    }

    public static function getTheCurrentYear()
    {
        $currentDate = static::getTheCurrentDate();
        var_dump($currentDate);
        return substr($currentDate, 5, 2);
    }

    public static function getTheFirstDayOfPreviousMonth()
    {
        $currentDate = date("Y-0n-0j", strtotime("first day of previous month"));
        var_dump($currentDate);
        exit();
        return substr($currentDate, 5, 2);
    }
}
