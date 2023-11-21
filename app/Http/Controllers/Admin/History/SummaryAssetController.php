<?php

namespace App\Http\Controllers\Admin\History;

use Illuminate\Http\Request;
use App\Exports\SummaryAssetExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\AssetData\AssetDataQueryServices;

class SummaryAssetController extends Controller
{
    protected $assetDataQueryServices;

    public function __construct(AssetDataQueryServices $assetDataQueryServices)
    {
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function index()
    {
        return view('pages.admin.report.asset.index');
    }

    public function download(Request $request)
    {
        return Excel::download(new SummaryAssetExport($request->id_lokasi, $request->id_kategori_asset), 'laporan-summary-asset.xlsx');
    }

    public function getSummaryAsset(Request $request)
    {
        try {
            $nilai_asset = $this->assetDataQueryServices->getValueAsset($request);
            $avg_depresiasi = $this->assetDataQueryServices->getAvgDepresiasiAsset();
            return response()->json([
                'success' => true,
                'data' => [
                    'asset' => $nilai_asset,
                    'avg_depresiasi' => number_format($avg_depresiasi, 2, ',', '.'),
                ],
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
