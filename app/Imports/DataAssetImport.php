<?php

namespace App\Imports;

use App\Imports\SheetAsset\DataAssetSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataAssetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data Asset Baru' => new DataAssetSheet(),
        ];
    }
}
