<?php

namespace App\Exports\SheetAsset;

use App\Models\Lokasi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataLokasiAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $lokasi = Lokasi::select('kode_lokasi', 'nama_lokasi', 'keterangan')->orderBy('created_at', 'ASC');
        return $lokasi;
    }

    public function title(): string
    {
        return 'Kode Lokasi Asset';
    }

    public function headings(): array
    {
        return ['Kode Lokasi', 'Nama Lokasi', 'Keterangan'];
    }
}
