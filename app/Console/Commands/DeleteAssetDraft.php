<?php

namespace App\Console\Commands;

use App\Models\LogAsset;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\FileHelpers;
use Illuminate\Console\Command;

class DeleteAssetDraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draft-asset:delete-softdelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete AssetData with is_draft = 1 and deleted_at is not null';

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
        $query = AssetData::withTrashed()
            ->where('is_pemutihan', '0')
            // ->where('is_draft', '1')
            ->whereNotNull('deleted_at')
            ->get();

        foreach ($query as $data) {
            $findImage = AssetImage::where('imageable_id', $data->id)->get();

            foreach ($findImage as $image) {
                $path = storage_path('app/images/asset');
                $pathOld = $path . '/' . $image->path;
                FileHelpers::removeFile($pathOld);
                $image->delete();
            }

            $logs = LogAsset::where('asset_id', $data->id)->get();
            foreach ($logs as $log) {
                $log->delete();
            }
            $data->forceDelete();
        }
        $this->info('Deleted ' . $query->count() . ' records of AssetData with is_draft = 1 and deleted_at is not null.');
    }
}
