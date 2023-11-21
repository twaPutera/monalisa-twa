<?php

namespace App\Services\GroupKategoriAsset;

use App\Models\GroupKategoriAsset;
use App\Http\Requests\GroupKategoriAsset\GroupKategoriAssetStoreRequest;
use App\Http\Requests\GroupKategoriAsset\GroupKategoriAssetUpdateRequest;

class GroupKategoriAssetCommandServices
{
    public function store(GroupKategoriAssetStoreRequest $request)
    {
        $request->validated();

        $groupKategoriAsset = new GroupKategoriAsset();
        $groupKategoriAsset->kode_group = $request->kode_group;
        $groupKategoriAsset->nama_group = $request->nama_group;
        $groupKategoriAsset->save();
    }

    public function update(GroupKategoriAssetUpdateRequest $request, $id)
    {
        $request->validated();

        $groupKategoriAsset = GroupKategoriAsset::findOrFail($id);
        $groupKategoriAsset->kode_group = $request->kode_group;
        $groupKategoriAsset->nama_group = $request->nama_group;
        $groupKategoriAsset->save();
    }

    public function destroy($id)
    {
        $groupKategoriAsset = GroupKategoriAsset::findOrFail($id);
        $groupKategoriAsset->delete();
    }
}
