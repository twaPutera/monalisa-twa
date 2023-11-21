<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\KategoriAsset\KategoriAssetQueryServices;
use App\Services\PemindahanAsset\PemindahanDatatableServices;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetStoreRequest;

class PemindahanAssetController extends Controller
{
    protected $pemindahanAssetCommandServices;
    protected $pemindahanDatatableServices;
    protected $pemindahanAssetQueryServices;
    protected $kategoriAssetQueryServices;

    public function __construct(
        PemindahanAssetCommandServices $pemindahanAssetCommandServices,
        PemindahanDatatableServices $pemindahanDatatableServices,
        PemindahanAssetQueryServices $pemindahanAssetQueryServices,
        KategoriAssetQueryServices $kategoriAssetQueryServices
    ) {
        $this->pemindahanAssetCommandServices = $pemindahanAssetCommandServices;
        $this->pemindahanDatatableServices = $pemindahanDatatableServices;
        $this->pemindahanAssetQueryServices = $pemindahanAssetQueryServices;
        $this->kategoriAssetQueryServices = $kategoriAssetQueryServices;
    }

    public function store(PemindahanAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->pemindahanAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan pemindahan asset',
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->pemindahanAssetQueryServices->findById($id);
            $asset = json_decode($data->detail_pemindahan_asset->json_asset_data);
            $kategori = $this->kategoriAssetQueryServices->findById($asset->id_kategori_asset);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data pemindahan asset',
                'data' => [
                    'pemindahan' => $data,
                    'kategori' => $kategori,
                ],
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function datatable(Request $request)
    {
        return $this->pemindahanDatatableServices->datatable($request);
    }

    public function printBast($id)
    {
        $data = $this->pemindahanAssetQueryServices->findById($id);
        $penerima = json_decode($data->json_penerima_asset);
        $penyerah = json_decode($data->json_penyerah_asset);
        $asset = json_decode($data->detail_pemindahan_asset->json_asset_data);
        $pdf = \PDF::loadView('pages.admin.pemindahan-asset.bast.index', compact('data', 'penerima', 'penyerah', 'asset'));
        return $pdf->stream();
        // return view('pages.admin.pemindahan-asset.bast.index', compact('data'));
    }
}
