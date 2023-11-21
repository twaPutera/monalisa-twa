<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\Pengaduan\PengaduanCommandServices;
use App\Http\Requests\Pengaduan\AssetPengaduanStoreRequest;

class AssetPengaduanController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetServiceCommandServices;
    protected $pengaduanCommandServices;
    public function __construct(
        PengaduanCommandServices $pengaduanCommandServices,
        AssetDataQueryServices $assetDataQueryServices
    ) {
        $this->pengaduanCommandServices = $pengaduanCommandServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function create($id)
    {
        $asset_data = $this->assetDataQueryServices->findById($id);
        if ($asset_data->is_pemutihan != 1) {
            return view('pages.user.asset.pengaduan.create', compact('asset_data'));
        }
        abort(404);
    }

    public function store(AssetPengaduanStoreRequest $request, string $id)
    {
        try {
            $asset_data = $this->assetDataQueryServices->findById($id);
            if ($asset_data->is_pemutihan != 1) {
                DB::beginTransaction();
                $asset_service = $this->pengaduanCommandServices->storeUserFromScan($request, $id);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menambahkan pengaduan asset',
                    'data' => $asset_service,
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'Asset Dalam Penghapusan, Tidak Dapat Diadukan',
            ], 500);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
