<?php

namespace App\Services\PemindahanAsset;

use Illuminate\Http\Request;
use App\Models\PemindahanAsset;

class PemindahanAssetQueryServices
{
    public function findById(string $id)
    {
        $pemindahan_asset = PemindahanAsset::query()
            ->with(['detail_pemindahan_asset', 'approval'])
            ->where('id', $id)
            ->first();

        return $pemindahan_asset;
    }

    public function findAll(Request $request)
    {
        $pemindahan_asset = PemindahanAsset::query();

        if (isset($request->with)) {
            $pemindahan_asset->with($request->with);
        }

        if (isset($request->guid_penerima_asset)) {
            $pemindahan_asset->where('guid_penerima_asset', $request->guid_penerima_asset);
        }

        if (isset($request->guid_penyerah_asset)) {
            $pemindahan_asset->where('guid_penyerah_asset', $request->guid_penyerah_asset);
        }

        if (isset($request->status)) {
            $pemindahan_asset->where('status', $request->status);
        }

        if (isset($request->id_asset)) {
            $pemindahan_asset->whereHas('detail_pemindahan_asset', function ($query) use ($request) {
                $query->where('id_asset', $request->id_asset);
            });
        }

        $pemindahan_asset = $pemindahan_asset->orderby('tanggal_pemindahan', 'DESC')->get();

        return $pemindahan_asset;
    }
}
