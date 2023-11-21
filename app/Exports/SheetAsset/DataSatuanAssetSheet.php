<?php

namespace App\Exports\SheetAsset;

use App\Models\SatuanAsset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataSatuanAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $satuan = SatuanAsset::select('kode_satuan', 'nama_satuan')->orderBy('created_at', 'ASC');
        return $satuan;
    }

    public function title(): string
    {
        return 'Kode Satuan Asset';
    }

    public function headings(): array
    {
        return ['Kode Satuan', 'Nama Satuan'];
    }
}
