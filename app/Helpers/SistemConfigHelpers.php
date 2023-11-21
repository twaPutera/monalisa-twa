<?php

namespace App\Helpers;

use App\Models\SistemConfig;

class SistemConfigHelpers
{
    public static function get($config)
    {
        $config = SistemConfig::where('config', $config)->first();
        return $config->value;
    }
}
