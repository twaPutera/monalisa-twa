<?php

namespace App\Services\SatuanInventori;

use Illuminate\Http\Request;
use App\Models\SatuanInventori;

class SatuanInventoriQueryServices
{
    public function findAll()
    {
        return SatuanInventori::all();
    }

    public function findById(string $id)
    {
        return SatuanInventori::findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = SatuanInventori::query();

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
