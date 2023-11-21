<?php

namespace App\Services\SistemConfig;

use App\Models\SistemConfig;

class SistemConfigQueryServices
{
    public function findAll()
    {
        return SistemConfig::all();
    }

    public function findById($id)
    {
        return SistemConfig::find($id);
    }

    public function findByConfig($config)
    {
        return SistemConfig::where('config', $config)->first();
    }
}
