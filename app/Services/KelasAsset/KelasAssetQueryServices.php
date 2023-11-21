<?php

namespace App\Services\KelasAsset;

use App\Models\KelasAsset;
use Illuminate\Http\Request;

class KelasAssetQueryServices
{
    public function findAll()
    {
        return KelasAsset::all();
    }

    public function findById(string $id)
    {
        return KelasAsset::findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = KelasAsset::query();

        if (isset($request->keyword)) {
            $data->where('nama_kelas', 'like', '%' . $request->keyword . '%')
                ->where(function ($query) use ($request) {
                    $query->orWhere('no_akun', 'like', '%' . $request->keyword . '%');
                });
        }

        $data = $data->orderby('nama_kelas', 'asc')
                ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_kelas . ' (' . $item->no_akun . ')',
            ];
        }

        return $results;
    }
}
