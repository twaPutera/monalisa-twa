<?php

namespace App\Services\KategoriInventori;

use Illuminate\Http\Request;
use App\Models\KategoriInventori;

class KategoriInventoriQueryServices
{
    public function findAll()
    {
        return KategoriInventori::all();
    }

    public function findById(string $id)
    {
        return KategoriInventori::findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = KategoriInventori::query();

        if (isset($request->keyword)) {
            $data->where('nama_kategori', 'like', '%' . $request->keyword . '%');
        }

        $data = $data->orderby('nama_kategori', 'asc')
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_kategori,
            ];
        }

        return $results;
    }
}
