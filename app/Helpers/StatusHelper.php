<?php

namespace App\Helpers;

class StatusHelper
{
    public static $langStatus = [
        '1' => '✅',
        '0' => '❌',
    ];

    public static function langStatusGet($index)
    {
        return self::$circleCardStatus[$index] ?? 'No';
    }

    public static $circleStatus = [
        '1' => '✅',
        '0' => '❌',
    ];

    public static function circleStatusGet($index)
    {
        return self::$circleCardStatus[$index] ?? 'No';
    }

    public static $cardStatus = [
        '1' => '✅',
        '0' => '❌',
    ];

    public static function cardStatusGet($index)
    {
        return self::$circleCardStatus[$index] ?? 'No';
    }

    public static $circleCardStatus = [
        '1' => '✅',
        '0' => '❌',
    ];

    public static function circleCardStatusGet($index)
    {
        return self::$circleCardStatus[$index] ?? 'No';
    }

    public static $circleCardSalary = [
        '0' => '❌',
        '1' => '✅',
    ];

    public static function circleCardSalaryGet($index)
    {
        return self::$circleCardStatus[$index] ?? 'No';
    }

    public static $circleCardStart = [
        '0' => '❌',
        '1' => '✅',
    ];

    public static function circleCardStartGet($index)
    {
        return self::$circleCardStatus[$index] ?? 'No';
    }

    public static $biletStatus = [
        '1' => '✅',
        '0' => '❌',
    ];

    public static function biletStatusGet($index)
    {
        return self::$biletStatus[$index] ?? 'No';
    }
}
