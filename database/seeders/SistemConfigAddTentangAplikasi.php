<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SistemConfigAddTentangAplikasi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = new \App\Models\SistemConfig();
        $config->config = 'tentang_aplikasi';
        $config->config_name = 'Tentang Aplikasi';
        $config->value = 'Tentang Aplikasi';
        $config->save();
    }
}
