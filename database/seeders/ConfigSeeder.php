<?php

namespace Database\Seeders;

use App\Models\SistemConfig;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sistem_config = new SistemConfig();
        $sistem_config->id = '8e3237c2-0e4f-4bc3-a696-2f3b9a6a93df';
        $sistem_config->config = 'min_asset_value';
        $sistem_config->config_name = 'Minimum Asset Value';
        $sistem_config->value = '100000';
        $sistem_config->save();
    }
}
