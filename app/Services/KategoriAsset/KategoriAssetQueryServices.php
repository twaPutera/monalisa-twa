<?php

namespace App\Services\KategoriAsset;

use Illuminate\Http\Request;
use App\Models\KategoriAsset;

class KategoriAssetQueryServices
{
    public function findAll()
    {
        return KategoriAsset::all();
    }

    public function findById(string $id)
    {
        return KategoriAsset::query()->with(['group_kategori_asset'])->findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = KategoriAsset::query();

        if (isset($request->keyword)) {
            $data->where('nama_kategori', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->id_group_kategori_asset)) {
            $data->where('id_group_kategori_asset', $request->id_group_kategori_asset);
        }

        $data = $data->orderby('nama_kategori', 'asc')
                ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_kategori,
                'dataKodeKategori' => $item->kode_kategori ?? '',
            ];
        }

        return $results;
    }
}
