<?php

namespace App\Services\Lokasi;

use App\Models\Lokasi;
use App\Models\AssetData;
use Illuminate\Http\Request;
use App\Models\PerencanaanServices;

class LokasiQueryServices
{
    public function findAll()
    {
        return Lokasi::all();
    }

    public function findById(string $id)
    {
        return Lokasi::findOrFail($id);
    }

    public function findByKodeLokasi(string $kode)
    {
        return Lokasi::where('kode_lokasi', $kode)->first();
    }

    public function findByParentId($id)
    {
        $lokasi = Lokasi::query()
            ->where('id_parent_lokasi', $id)->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama_lokasi,
                    'children' => $this->findByParentId($item->id),
                ];
            });
        return $lokasi;
    }

    public function generateSelect2(Request $request)
    {
        $id_parent_lokasi = $request->id_parent_lokasi === 'root' ? null : $request->id_parent_lokasi;
        if ($id_parent_lokasi == null) {
            $parent[] = [
                'id' => 'root',
                'text' => '(Parent) Universitas Pertamina',
            ];
        }else{
            $parentLokasi = Lokasi::query()
                ->where('id', $id_parent_lokasi)
                ->first();
            $parent[] = [
                'id' => $parentLokasi->id,
                'text' => '(Parent) ' . $parentLokasi->nama_lokasi,
            ];
        }
        $lokasi = Lokasi::query()
            ->where('id_parent_lokasi', $id_parent_lokasi)->get()
            ->map(function ($item) use ($parent) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama_lokasi,
                ];
            });
        $lokasi = array_merge($parent, $lokasi->toArray());
        return $lokasi;
    }

    public function generateAllSelect2(Request $request)
    {
        $arraySelect2 = [];
        if (!isset($request->keyword)){
            $arraySelect2[] = [
                'id' => 'root',
                'text' => 'Universitas Pertamina',
            ];
        }

        $lokasi = Lokasi::query();
        if (isset($request->keyword)) {
            $lokasi->where('nama_lokasi', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->id_asset)) {
            $perencanaan = PerencanaanServices::where('id', $request->id_asset)->first();
            if ($perencanaan) {
                $asset = AssetData::where('id', $perencanaan->id_asset_data)->first();
                if ($asset) {
                    $lokasi->where('id', $asset->id_lokasi);
                }
            }
        }

        $lokasi = $lokasi->where('id_parent_lokasi', null)->get();
        
        foreach ($lokasi as $item) {
            $arraySelect2[] = [
                'id' => $item->id,
                'text' => '- ' . $item->nama_lokasi,
            ];
            $arraySelect2 = array_merge($arraySelect2, $this->getSelect2Children($item->id, $request, 2));
        }
        return $arraySelect2;
    }

    public function getSelect2Children($id_parent_lokasi, $request, $iterasi)
    {
        $strip = str_repeat('-', $iterasi);
        $arraySelect2 = [];
        $lokasi = Lokasi::query();

        if (isset($request->keyword)) {
            $lokasi->where('nama_lokasi', 'like', '%' . $request->keyword . '%');
        }
        $lokasi = $lokasi->where('id_parent_lokasi', $id_parent_lokasi)->get();
        $iterasi++;
        foreach ($lokasi as $item){
            $arraySelect2[] = [
                'id' => $item->id,
                'text' => $strip . ' ' . $item->nama_lokasi,
            ];
            $arraySelect2 = array_merge($arraySelect2, $this->getSelect2Children($item->id, $request, $iterasi));
        }
        return $arraySelect2;
    }
}
