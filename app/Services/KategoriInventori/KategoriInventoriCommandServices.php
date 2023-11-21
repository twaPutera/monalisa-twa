<?php

namespace App\Services\KategoriInventori;

use App\Models\KategoriInventori;
use App\Http\Requests\KategoriInventori\KategoriInventoriStoreRequest;
use App\Http\Requests\KategoriInventori\KategoriInventoriUpdateRequest;

class KategoriInventoriCommandServices
{
    public function store(KategoriInventoriStoreRequest $request)
    {
        $request->validated();

        $kategori_inventori = new KategoriInventori();
        $kategori_inventori->kode_kategori = $request->kode_kategori;
        $kategori_inventori->nama_kategori = $request->nama_kategori;
        $kategori_inventori->save();

        return $kategori_inventori;
    }

    public function update(string $id, KategoriInventoriUpdateRequest $request)
    {
        $request->validated();

        $kategori_inventori = KategoriInventori::findOrFail($id);
        $kategori_inventori->kode_kategori = $request->kode_kategori;
        $kategori_inventori->nama_kategori = $request->nama_kategori;
        $kategori_inventori->save();

        return $kategori_inventori;
    }

    public function delete(string $id)
    {
        $kategori_inventori = KategoriInventori::findOrFail($id);
        $kategori_inventori->delete();

        return $kategori_inventori;
    }
}
