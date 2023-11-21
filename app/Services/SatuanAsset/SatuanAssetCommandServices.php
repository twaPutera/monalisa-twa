<?php

namespace App\Services\SatuanAsset;

use App\Models\SatuanAsset;
use App\Http\Requests\SatuanAsset\SatuanAssetStoreRequest;
use App\Http\Requests\SatuanAsset\SatuanAssetUpdateRequest;

class SatuanAssetCommandServices
{
    public function store(SatuanAssetStoreRequest $request)
    {
        $request->validated();

        $satuan_asset = new SatuanAsset();
        $satuan_asset->kode_satuan = $request->kode_satuan;
        $satuan_asset->nama_satuan = $request->nama_satuan;
        $satuan_asset->save();

        return $satuan_asset;
    }

    public function update(string $id, SatuanAssetUpdateRequest $request)
    {
        $request->validated();

        $satuan_asset = SatuanAsset::findOrFail($id);
        $satuan_asset->kode_satuan = $request->kode_satuan;
        $satuan_asset->nama_satuan = $request->nama_satuan;
        $satuan_asset->save();

        return $satuan_asset;
    }

    public function delete(string $id)
    {
        $satuan_asset = SatuanAsset::findOrFail($id);
        $satuan_asset->delete();

        return $satuan_asset;
    }
}
