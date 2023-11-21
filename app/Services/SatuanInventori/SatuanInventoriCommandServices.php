<?php

namespace App\Services\SatuanInventori;

use App\Models\SatuanInventori;
use App\Http\Requests\SatuanInventori\SatuanInventoriStoreRequest;
use App\Http\Requests\SatuanInventori\SatuanInventoriUpdateRequest;

class SatuanInventoriCommandServices
{
    public function store(SatuanInventoriStoreRequest $request)
    {
        $request->validated();

        $satuan_asset = new SatuanInventori();
        $satuan_asset->kode_satuan = $request->kode_satuan;
        $satuan_asset->nama_satuan = $request->nama_satuan;
        $satuan_asset->save();

        return $satuan_asset;
    }

    public function update(string $id, SatuanInventoriUpdateRequest $request)
    {
        $request->validated();

        $satuan_asset = SatuanInventori::findOrFail($id);
        $satuan_asset->kode_satuan = $request->kode_satuan;
        $satuan_asset->nama_satuan = $request->nama_satuan;
        $satuan_asset->save();

        return $satuan_asset;
    }

    public function delete(string $id)
    {
        $satuan_asset = SatuanInventori::findOrFail($id);
        $satuan_asset->delete();

        return $satuan_asset;
    }
}
