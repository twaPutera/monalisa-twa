<?php

namespace App\Http\Controllers\User\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;

class ApprovalController extends Controller
{
    protected $pemindahanAssetCommandServices;
    protected $pemindahanAssetQueryServices;

    public function __construct(
        PemindahanAssetCommandServices $pemindahanAssetCommandServices,
        PemindahanAssetQueryServices $pemindahanAssetQueryServices
    ) {
        $this->pemindahanAssetCommandServices = $pemindahanAssetCommandServices;
        $this->pemindahanAssetQueryServices = $pemindahanAssetQueryServices;
    }

    public function index()
    {
        return view('pages.user.approval.index');
    }

    public function getAllData(Request $request)
    {
        try {
            $pemindahan_asset = $this->pemindahanAssetQueryServices->findAll($request);
            $pemindahan_asset->map(function ($item) {
                $item->link_detail = route('user.asset-data.pemindahan.detail', ['id' => $item->id]);
                $item->asset = json_decode($item->detail_pemindahan_asset->json_asset_data);
                $item->penerima = json_decode($item->json_penerima_asset);
                $item->penyerah = json_decode($item->json_penyerah_asset);
                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $pemindahan_asset,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
