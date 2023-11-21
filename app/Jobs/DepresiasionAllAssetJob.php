<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Helpers\DepresiasiHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DepresiasionAllAssetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assets = DepresiasiHelpers::getDataAssetDepresiasi(date('Y-m-d'));

        logger('Depresiasi Asset: ', [count($assets)]);

        foreach ($assets as $asset) {
            logger('Depresiasi Asset: ' . $asset->id, [$asset]);
            try {
                DB::beginTransaction();
                if(isset($asset->tgl_pelunasan)){ //kondisi ini ditambahkan oleh wahyu
                    $data = DepresiasiHelpers::depresiasiAsset($asset, date('Y-m-d'));
                }else{
                    continue; //ditambahkan oleh wahyu
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                logger('Error Depresiasi Asset: ' . $asset->id, [$th->getMessage()]);
            }
        }

        logger('Depresiasi Berjalan pada Tanggal ' . date('Y-m-d'), []);
    }
}
