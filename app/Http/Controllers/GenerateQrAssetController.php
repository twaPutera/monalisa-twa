<?php

namespace App\Http\Controllers;

use App\Models\AssetData;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelpers;
use App\Jobs\GenerateAllQrCodeJob;

class GenerateQrAssetController extends Controller
{
    public function index()
    {
        return view('test-front.generate-qr');
    }

    public function generateQrAsset(Request $request)
    {
        $limit = 100;
        $asset = AssetData::query()
            ->where('is_draft', '0')
            ->where('is_pemutihan', '0')
            ->select('id', 'kode_asset')
            ->limit($limit * $request->page)
            ->offset($limit * ($request->page - 1))
            ->get();

        foreach ($asset as $key => $value) {
            if (\File::exists(storage_path('app/images/qr-code/qr-asset-' . $value->kode_asset . '.png'))) {
                \File::delete(storage_path('app/images/qr-code/qr-asset-' . $value->kode_asset . '.png'));
            }

            $qr_name = 'qr-asset-' . $value->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($value->kode_asset, $path);

            $update = AssetData::find($value->id);
            $update->qr_code = $qr_name;
            $update->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Generate QR Code Asset Berhasil',
            'page' => $request->page,
            'next_page' => $request->page + 1,
            'last_page' => ceil(AssetData::query()->where('is_draft', '0')->where('is_pemutihan', '0')->count() / $limit) == $request->page ? true : false,
        ]);
    }

    public function generateQueueQrAsset(Request $request)
    {
        $count_asset = AssetData::query()->where('is_draft', '0')
            ->where('is_draft', '0')
            ->where('is_pemutihan', '0')
            ->count();

        $limit = 100;

        $total_page = ceil($count_asset / $limit);

        for ($i = 1; $i <= $total_page; $i++) {
            GenerateAllQrCodeJob::dispatch($i);
        }

        return response()->json([
            'success' => true,
            'message' => 'Generate QR Code Asset Sedang Berlangsung Di Queue',
            'total_page' => $total_page,
        ]);
    }
}
