<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;

class AssetController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetServiceQueryServices;

    public function __construct(
        AssetDataQueryServices $assetDataQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices
    ) {
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
    }

    public function getDataAssetByUser(Request $request)
    {
        try {
            $user = \Session::get('user', null);
            $data = $this->assetDataQueryServices->getDataAssetForDashboardUser($user->guid ?? '0')->map(function ($item) use ($user) {
                $item->link_detail = route('user.asset-data.detail', $item->id);
                $item->tanggal_diterima = date('d/m/Y', strtotime($item->tgl_register));
                $item->status_diterima = 'Diterima';
                $pemindahan_asset = $this->assetDataQueryServices->checkIsAssetOnPemindahanAsset($item->id, $user->guid);
                if (isset($pemindahan_asset)) {
                    if ($pemindahan_asset->pemindahan_asset->status == 'pending') {
                        $item->link_detail = route('user.asset-data.pemindahan.detail', $pemindahan_asset->id_pemindahan_asset);
                    }
                    $item->tanggal_diterima = $pemindahan_asset->pemindahan_asset->status != 'pending' ? date('d/m/Y', strtotime($pemindahan_asset->pemindahan_asset->tanggal_pemindahan)) : '-';
                    $item->status_diterima = $pemindahan_asset->pemindahan_asset->status != 'pending' ? 'Diterima' : 'Belum Diterima';
                }
                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function detail($id)
    {
        $array_search = ['peminjaman' => true];
        $asset_data = $this->assetDataQueryServices->findById($id, $array_search);
        $last_service = $this->assetServiceQueryServices->findLastestLogByAssetId($id);
        return view('pages.user.asset.detail', compact('asset_data', 'last_service'));
    }

    public function getDataAssetSelect2(Request $request)
    {
        try {
            $asset = $this->assetDataQueryServices->getDataAssetSelect2($request);

            return response()->json([
                'success' => true,
                'data' => $asset,
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
