<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;

class ScanQrCodeController extends Controller
{
    protected $assetDataQueryServices;
    public function __construct(AssetDataQueryServices $assetDataQueryServices)
    {
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function index()
    {
        return view('pages.user.scan.index');
    }

    public function find(Request $request)
    {
        try {
            $asset = $this->assetDataQueryServices->findBykode($request);
            if ($asset) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mendapatkan data asset, halaman akan dialihkan',
                    'data' => $asset,
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Data asset tidak ditemukan',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
