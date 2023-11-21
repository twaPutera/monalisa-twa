<?php

namespace App\Console\Commands;

use App\Models\AssetData;
use Illuminate\Console\Command;

class GenerateNoUrutFormat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset-data:generate-no-urut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up no urut in all asset data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filteredData = AssetData::where('is_pemutihan', '0')->get();
        $count = 0;
        foreach ($filteredData as $data) {
            $noUrut = strval($data->no_urut);
            if (strlen($noUrut) < 5) {
                $noUrut = str_pad($noUrut, 5, '0', STR_PAD_LEFT);
                $data->no_urut = $noUrut;
                $count++;
            }
            $data->save();
        }
        $this->info('Updated ' . $count . ' records of AssetData no_urut.');
    }
}
