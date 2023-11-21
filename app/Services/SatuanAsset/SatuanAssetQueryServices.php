<?php

namespace App\Services\SatuanAsset;

use App\Models\SatuanAsset;
use Illuminate\Http\Request;

class SatuanAssetQueryServices
{
    public function findAll()
    {
        return SatuanAsset::all();
    }

    public function findById(string $id)
    {
        return SatuanAsset::findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = SatuanAsset::query();

        if (isset($request->keyword)) {
            $data->where('nama_satuan', 'like', '%' . $request->keyword . '%');
        }

        $data = $data->orderby('nama_satuan', 'asc')
                ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_satuan,
            ];
        }

        return $results;
    }
}
