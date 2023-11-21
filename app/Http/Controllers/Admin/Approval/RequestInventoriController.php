<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\RequestInventoriUpdate;
use App\Services\InventarisData\InventarisDataCommandServices;

class RequestInventoriController extends Controller
{
    protected $inventarisDataCommandServices;

    public function __construct(
        InventarisDataCommandServices $inventarisDataCommandServices
    ) {
        $this->inventarisDataCommandServices = $inventarisDataCommandServices;
    }

    public function index()
    {
        return view('pages.admin.approval.request-inventori.index');
    }

    public function changeStatusApproval(RequestInventoriUpdate $request, $id)
    {
        try {
            $data = $this->inventarisDataCommandServices->changeApprovalStatus($request, $id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status approval',
                'data' => [
                    'request_inventori' => $data,
                    'url' => route('admin.permintaan-inventaris.realisasi', $data->id),
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
