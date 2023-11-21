<?php

namespace App\Jobs;

use App\Models\ZipFile;
use Illuminate\Log\Logger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteZipFileJob implements ShouldQueue
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
        $files = ZipFile::query()->get();

        foreach ($files as $file) {
            if (\File::exists(storage_path($file->path))) {
                // Delete the file
                \File::delete(storage_path($file->path));
            }
            // Delete the record
            $file->delete();
        }

        logger('Deleted all zip files at ' . date('Y-m-d H:i:s'), []);
    }
}
