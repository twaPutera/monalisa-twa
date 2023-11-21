<?php

namespace App\Services\Vendor;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorQueryServices
{
    public function findAll()
    {
        return Vendor::all();
    }

    public function findById($id)
    {
        return Vendor::findOrFail($id);
    }

    public function findByKode($kode)
    {
        return Vendor::where('kode_vendor', $kode)->first();
    }

    public function getDataSelect2(Request $request)
    {
        $data = Vendor::query();

        if (isset($request->keyword)) {
            $data->where('nama_vendor', 'like', '%' . $request->keyword . '%');
        }

        $data = $data->orderby('nama_vendor', 'asc')
                ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_vendor,
            ];
        }

        return $results;
    }
}
