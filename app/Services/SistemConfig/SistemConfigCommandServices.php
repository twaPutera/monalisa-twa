<?php

namespace App\Services\SistemConfig;

use App\Models\SistemConfig;
use App\Http\Requests\SistemConfig\SistemConfigUpdateRequest;

class SistemConfigCommandServices
{
    public function updateAll(SistemConfigUpdateRequest $request)
    {
        $request->validated();
        $data = [];
        foreach ($request->config as $key => $value) {
            $config = SistemConfig::where('config', $key)->first();
            if ($config) {
                $config->value = $value;
                $config->save();
                $data[] = $config;
            }
        }
        return $data;
    }
}
