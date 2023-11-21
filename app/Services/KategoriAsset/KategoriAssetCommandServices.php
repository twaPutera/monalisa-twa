<?php

namespace App\Services\KategoriAsset;

use App\Models\KategoriAsset;
use App\Http\Requests\KategoriAsset\KategoriAssetStoreRequest;
use App\Http\Requests\KategoriAsset\KategoriAssetUpdateRequest;

class KategoriAssetCommandServices
{
    public function store(KategoriAssetStoreRequest $request)
    {
        $request->validated();

        $kategori_asset = new KategoriAsset();
        $kategori_asset->id_group_kategori_asset = $request->id_group_kategori_asset;
        $kategori_asset->kode_kategori = $request->kode_kategori;
        $kategori_asset->nama_kategori = $request->nama_kategori;
        $kategori_asset->umur_asset = $request->umur_asset;
        $kategori_asset->save();

        return $kategori_asset;
    }

    public function update(string $id, KategoriAssetUpdateRequest $request)
    {
        $request->validated();

        $kategori_asset = KategoriAsset::findOrFail($id);
        $kategori_asset->id_group_kategori_asset = $request->id_group_kategori_asset;
        $kategori_asset->kode_kategori = $request->kode_kategori;
        $kategori_asset->nama_kategori = $request->nama_kategori;
        $kategori_asset->umur_asset = $request->umur_asset;
        $kategori_asset->save();

        return $kategori_asset;
    }

    public function delete(string $id)
    {
        $kategori_asset = KategoriAsset::findOrFail($id);
        $kategori_asset->delete();

        return $kategori_asset;
    }
}
