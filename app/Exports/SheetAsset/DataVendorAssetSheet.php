<?php

namespace App\Exports\SheetAsset;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataVendorAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $vendor = Vendor::select('kode_vendor', 'nama_vendor')->orderBy('created_at', 'ASC');
        return $vendor;
    }

    public function title(): string
    {
        return 'Kode Vendor Asset';
    }

    public function headings(): array
    {
        return ['Kode Vendor', 'Nama Vendor'];
    }
}
