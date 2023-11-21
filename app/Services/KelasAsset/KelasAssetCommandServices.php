<?php

namespace App\Services\KelasAsset;

use App\Models\KelasAsset;
use App\Http\Requests\KelasAsset\KelasAssetStoreRequest;
use App\Http\Requests\KelasAsset\KelasAssetUpdateRequest;

class KelasAssetCommandServices
{
    public function store(KelasAssetStoreRequest $request)
    {
        $request->validated();

        $kategori_inventori = new KelasAsset();
        $kategori_inventori->no_akun = $request->no_akun;
        $kategori_inventori->nama_kelas = $request->nama_kelas;
        $kategori_inventori->save();

        return $kategori_inventori;
    }

    public function update(string $id, KelasAssetUpdateRequest $request)
    {
        $request->validated();

        $kategori_asset = KelasAsset::findOrFail($id);
        $kategori_asset->no_akun = $request->no_akun;
        $kategori_asset->nama_kelas = $request->nama_kelas;
        $kategori_asset->save();

        return $kategori_asset;
    }

    public function delete(string $id)
    {
        $kategori_asset = KelasAsset::findOrFail($id);
        $kategori_asset->delete();

        return $kategori_asset;
    }
}
