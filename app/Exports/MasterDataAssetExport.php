<?php

namespace App\Exports;

use App\Exports\SheetAsset\DataAssetSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\SheetAsset\DataKelasAssetSheet;
use App\Exports\SheetAsset\DataLokasiAssetSheet;
use App\Exports\SheetAsset\DataSatuanAssetSheet;
use App\Exports\SheetAsset\DataVendorAssetSheet;
use App\Exports\SheetAsset\DataKategoriAssetSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MasterDataAssetExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $kategori_asset = new DataKategoriAssetSheet;
        $kelas_asset = new DataKelasAssetSheet;
        $satuan_asset = new DataSatuanAssetSheet;
        $vendor_asset = new DataVendorAssetSheet;
        $asset_data = new DataAssetSheet;
        $lokasi = new DataLokasiAssetSheet;

        // Order Sheet
        $sheets = [$asset_data, $kelas_asset, $kategori_asset, $satuan_asset, $vendor_asset, $lokasi];

        return $sheets;
    }
}
