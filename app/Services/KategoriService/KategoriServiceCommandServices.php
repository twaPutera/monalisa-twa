<?php

namespace App\Services\KategoriService;

use App\Models\KategoriService;
use App\Http\Requests\KategoriService\KategoriServiceStoreRequest;
use App\Http\Requests\KategoriService\KategoriServiceUpdateRequest;

class KategoriServiceCommandServices
{
    public function store(KategoriServiceStoreRequest $request)
    {
        $request->validated();

        $kategori_service = new KategoriService();
        $kategori_service->kode_service = $request->kode_service;
        $kategori_service->nama_service = $request->nama_service;
        $kategori_service->save();

        return $kategori_service;
    }

    public function update(string $id, KategoriServiceUpdateRequest $request)
    {
        $request->validated();

        $kategori_service = KategoriService::findOrFail($id);
        $kategori_service->kode_service = $request->kode_service;
        $kategori_service->nama_service = $request->nama_service;
        $kategori_service->save();

        return $kategori_service;
    }

    public function delete(string $id)
    {
        $kategori_service = KategoriService::findOrFail($id);
        $kategori_service->delete();

        return $kategori_service;
    }
}
