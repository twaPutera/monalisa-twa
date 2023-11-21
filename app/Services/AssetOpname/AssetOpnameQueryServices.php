<?php

namespace App\Services\AssetOpname;

use App\Models\LogAssetOpname;
use App\Models\PerencanaanServices;

class AssetOpnameQueryServices
{
    public function findById(string $id)
    {
        $data = LogAssetOpname::query()
            ->with(['image'])
            ->where('id', $id)
            ->firstOrFail();

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.opname.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }

    public function findPerencanaanByTanggal($tanggal, $id_asset)
    {
        $find = PerencanaanServices::where('tanggal_perencanaan', $tanggal)->where('id_asset_data', $id_asset)->where('status', 'pending')->first();
        return $find;
    }
}
