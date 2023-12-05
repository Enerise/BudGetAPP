<?php

namespace App;

use DateTime;

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
    }

    public static function getTheLastDayOfCurrentMonth()
    {
        return date('Y-m-t');
    }

    public static function getTheCurrentYear()
    {
        $currentDate = static::getTheCurrentDate();
        var_dump($currentDate);
        return substr($currentDate, 5, 2);
    }

    public static function getTheFirstDayOfCurrentYear()
    {
        $date = date('Y-m-t');
        $year = substr($date, 0, 4);
        return $year . "-01-01";
    }

    public static function getTheLastDayOfCurrentYear()
    {
        $date = date('Y-m-t');
        $year = substr($date, 0, 4);
        return $year . "-12-31";
    }

    public static function getTheDateWithFirstDayOfPreviousMonth()
    {
        $dateWithFirstDayOfPreviousMonth = date("Y-n-0j", strtotime("first day of previous month"));

        $fullDate = static::correctTheDate($dateWithFirstDayOfPreviousMonth);
        return $fullDate;
    }

    public static function getTheDateWithLastDayOfPreviousMonth()
    {
        $dateWithLastDayOfPreviousMonth =  date("Y-n-j", strtotime("last day of previous month"));

        $fullDate = static::correctTheDate($dateWithLastDayOfPreviousMonth);
        return $fullDate;
    }

    public static function correctTheDate($date)
    {

        $monthOfPreviousMonth = substr($date, 5, 2);

        if (($monthOfPreviousMonth == "10") || ($monthOfPreviousMonth == "11") || ($monthOfPreviousMonth == "12")) {
            return $date;
        } else {
            $day = substr($date, 7, 2);
            $month =  substr($date, 5, 1);
            $year = substr($date, 0, 5);
            $fullDate = $year . "0" . $month . "-" . $day;
            return $fullDate;
        }
    }

    public static function getTheFirstDayOfMonthFromUser($dateFromUser)
    {
        $dateWithoutDay = substr($dateFromUser, 0, 8);

        return $dateWithoutDay . "01";
    }

    public static function getTheYearFromUser($dateFromUser)
    {
        return substr($dateFromUser, 0, 4);;
    }

    public static function getTheMonthFromUser($dateFromUser)
    {
        return substr($dateFromUser, 5, 2);
    }

    public static function getTheDayFromUser($dateFromUser)
    {
        return substr($dateFromUser, 8, 2);
    }

    public static function getTheLastDayOfMonthFromUser($dateFromUser)
    {
        $year = static::getTheYearFromUser($dateFromUser);
        $month = static::getTheMonthFromUser($dateFromUser);
        $day = static::getTheDayFromUser($dateFromUser);

        $dateFromUser = $year . "-" . $month . "-" . $day;
        $date = new DateTime($dateFromUser);
        $date->modify('last day of this month');
        return $date->format('Y-m-d');
    }
}
