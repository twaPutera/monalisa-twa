<?php

namespace App\Exports\SheetAsset;

use App\Models\KategoriAsset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataKategoriAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $kategori_asset = KategoriAsset::select('kategori_assets.kode_kategori', 'kategori_assets.nama_kategori', 'kategori_assets.umur_asset', 'group_kategori_assets.kode_group', 'group_kategori_assets.nama_group')->join('group_kategori_assets', 'group_kategori_assets.id', '=', 'kategori_assets.id_group_kategori_asset')->orderBy('kategori_assets.created_at', 'ASC');
        return $kategori_asset;
    }

    public function title(): string
    {
        return 'Kode Jenis Asset';
    }

    public function headings(): array
    {
        return ['Kode Jenis Asset', 'Nama Jenis Asset', 'Masa Manfaat Komersial', 'Kode Kelompok Asset', 'Nama Kelompok Asset',];
    }
}
