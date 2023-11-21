<?php

namespace App\Http\Controllers\Admin\Approval;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetChangeStatusRequest;

class PemindahanController extends Controller
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
        return view('pages.admin.approval.pemindahan.index');
    }

    public function changeStatusApproval(PemindahanAssetChangeStatusRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->pemindahanAssetCommandServices->changeStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status pemindahan asset',
                'data' => $data,
            ], 200);
            //code...
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
