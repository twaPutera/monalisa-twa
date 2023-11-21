<?php

namespace Database\Seeders;

use App\Models\SistemConfig;
use Illuminate\Database\Seeder;

class AddConfigMinNoUrutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = new SistemConfig();
        $config->config = 'min_no_urut';
        $config->value = '5';
        $config->config_name = 'Minimum Nomor Urut';
        $config->save();
    }
}
