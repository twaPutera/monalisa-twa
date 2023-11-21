<?php

namespace App\Jobs;

use App\Models\AssetData;
use Illuminate\Bus\Queueable;
use App\Helpers\QrCodeHelpers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateAllQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $page;
    public $timeout = 0;
    /**
     * Create a new job instance.
     *
     * @param mixed $page
     *
     * @return void
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $limit = 100;
        $asset = AssetData::query()
            ->where('is_draft', '0')
            ->where('is_pemutihan', '0')
            ->select('id', 'kode_asset')
            ->limit($limit * $this->page)
            ->offset($limit * ($this->page - 1))
            ->get();

        foreach ($asset as $key => $value) {
            if (\File::exists(storage_path('app/images/qr-code/qr-asset-' . $value->kode_asset . '.png'))) {
                \File::delete(storage_path('app/images/qr-code/qr-asset-' . $value->kode_asset . '.png'));
            }

            $qr_name = 'qr-asset-' . $value->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($value->kode_asset, $path);

            $update = AssetData::find($value->id);
            $update->qr_code = $qr_name;
            $update->save();
        }

        logger('Generate QR Code Asset Berhasil', [$this->page]);
    }
}
