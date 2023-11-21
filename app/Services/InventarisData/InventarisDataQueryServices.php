<?php

namespace App\Services\InventarisData;

use Illuminate\Http\Request;
use App\Models\InventoriData;
use App\Models\RequestInventori;
use App\Models\LogRequestInventori;

class InventarisDataQueryServices
{
    public function findAll()
    {
        return InventoriData::all();
    }

    public function findPermintaan(string $id)
    {
        $query = RequestInventori::find($id);
        return $query;
    }
    public function findAllRequest(Request $request)
    {
        $query = RequestInventori::query();

        if ($request->has('with')) {
            $query->with($request->with);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('statusArray')) {
            $query->whereIn('status', $request->statusArray);
        }

        if ($request->has('created_by')) {
            $query->where('guid_pengaju', $request->created_by);
        }

        $query = $query->get();
        return $query;
    }

    public function findRequestById(string $id)
    {
        return RequestInventori::query()
            ->with(['detail_request_inventori', 'detail_request_inventori.inventori', 'detail_request_inventori.inventori.kategori_inventori'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function findAllLog(Request $request)
    {
        $log_inventori = LogRequestInventori::query();
        if (isset($request->with)) {
            $log_inventori->with($request->with);
        }
        $log_inventori->orderBy('created_at', 'desc');
        $log_inventori->where('request_inventori_id', $request->id_request);
        $log_inventori = $log_inventori->get();
        return $log_inventori;
    }

    public function findById(string $id)
    {
        return InventoriData::with(['satuan_inventori', 'kategori_inventori'])->findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = InventoriData::query();

        if (isset($request->keyword)) {
            $data->where('nama_inventori', 'like', '%' . $request->keyword . '%')
                ->where(function ($query) use ($request) {
                    $query->orWhere('kode_inventori', 'like', '%' . $request->keyword . '%');
                });
        }

        if (isset($request->id_kategori_inventori)) {
            $data->where('id_kategori_inventori', $request->id_kategori_inventori);
        }

        $data = $data->orderby('nama_inventori', 'asc')
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_inventori . ' (' . $item->kode_inventori . ')',
            ];
        }

        return $results;
    }
}
