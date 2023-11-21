<?php

namespace App\Helpers;

class DateIndoHelpers
{
    public function __construct()
    {
        //
    }

    public static function formatDateToIndo($date, $withDayName = false)
    {
        $dateArray = explode('-', $date);
        $monthname = self::getMonthName($dateArray[1]);
        $fulldate = $dateArray[2] . ' ' . $monthname . ' ' . $dateArray[0];
        if ($withDayName) {
            $dayname = self::getDayName($date);
            $fulldate = $dayname . ', ' . $fulldate;
        }
        return $fulldate;
    }

    public static function getMonthName($month)
    {
        $month = (int) $month;
        $arrayMonth = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $arrayMonth[$month];
    }

    public static function getDayName($date)
    {
        $day = (int) date('N', strtotime($date));
        $arrayDay = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $arrayDay[$day];
    }

    public static function getDiffMinutesFromTwoDates($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $diff = abs($date1 - $date2);
        $diff = $diff / (60);
        return $diff;
    }
}
