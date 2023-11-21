<?php

namespace App\Http\Controllers\Admin\Approval;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\PeminjamanApprovalUpdate;
use App\Services\PeminjamanAsset\PeminjamanAssetCommandServices;

class PeminjamanController extends Controller
{
    protected $peminjamanAssetCommandServices;
    public function __construct(
        PeminjamanAssetCommandServices $peminjamanAssetCommandServices
    ) {
        $this->peminjamanAssetCommandServices = $peminjamanAssetCommandServices;
    }

    public function index()
    {
        return view('pages.admin.approval.peminjaman.index');
    }

    public function changeStatusApproval(PeminjamanApprovalUpdate $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->peminjamanAssetCommandServices->changeApprovalStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status approval',
                'data' => [
                    'peminjaman' => $data,
                    'url' => route('admin.peminjaman.detail', $data->id),
                ],
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function changeStatusApprovaPerpanjangan(PeminjamanApprovalUpdate $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->peminjamanAssetCommandServices->changeApprovalStatusPerpanjangan($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status approval',
                'data' => [
                    'peminjaman' => $data,
                    'url' => route('admin.peminjaman.detail', $data->id_peminjaman_asset),
                ],
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
