<?php

namespace App\Exports\SheetAsset;

use App\Models\KelasAsset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataKelasAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $data_kelas = KelasAsset::select('no_akun', 'nama_kelas')->orderBy('created_at', 'ASC');
        return $data_kelas;
    }

    public function title(): string
    {
        return 'Kode Akun';
    }

    public function headings(): array
    {
        return ['No Akun', 'Nama Kelas'];
    }
}
