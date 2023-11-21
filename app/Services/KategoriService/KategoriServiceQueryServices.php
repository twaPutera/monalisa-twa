<?php

namespace App\Services\KategoriService;

use Illuminate\Http\Request;
use App\Models\KategoriService;

class KategoriServiceQueryServices
{
    public function findAll()
    {
        return KategoriService::all();
    }

    public function findById(string $id)
    {
        return KategoriService::findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = KategoriService::query();

        if (isset($request->keyword)) {
            // $data->where('nama_service', 'like', '%' . $request->keyword . '%')
            //     ->where(function ($query) use ($request) {
            //         $query->orWhere('kode_service', 'like', '%' . $request->keyword . '%');
            //     });
            $data->where('nama_service', 'like', '%' . $request->keyword . '%')
                ->orWhere('kode_service', 'like', '%' . $request->keyword . '%');
        }

        $data = $data->orderby('nama_service', 'asc')
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_service . ' (' . $item->kode_service . ')',
            ];
        }

        return $results;
    }
}
