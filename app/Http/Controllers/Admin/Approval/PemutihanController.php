<?php

namespace App\Http\Controllers\Admin\Approval;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\PemutihanApprovalUpdate;
use App\Services\PemutihanAsset\PemutihanAssetCommandServices;

class PemutihanController extends Controller
{
    protected $pemutihanAssetCommandServices;

    public function __construct(
        PemutihanAssetCommandServices $pemutihanAssetCommandServices
    ) {
        $this->pemutihanAssetCommandServices = $pemutihanAssetCommandServices;
    }

    public function index()
    {
        return view('pages.admin.approval.pemutihan.index');
    }

    public function changeStatusApproval(PemutihanApprovalUpdate $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->pemutihanAssetCommandServices->changeApprovalStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status approval',
                'data' => $data,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
