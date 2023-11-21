<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Vendor\VendorQueryServices;
use App\Services\Vendor\VendorCommandServices;
use App\Http\Requests\Vendor\VendorStoreRequest;
use App\Services\Vendor\VendorDatatableServices;
use App\Http\Requests\Vendor\VendorUpdateRequest;

class VendorController extends Controller
{
    protected $vendorQueryServices;
    protected $vendorCommandServices;
    protected $vendorDatatableServices;

    public function __construct(
        VendorQueryServices $vendorQueryServices,
        VendorCommandServices $vendorCommandServices,
        VendorDatatableServices $vendorDatatableServices
    ) {
        $this->vendorQueryServices = $vendorQueryServices;
        $this->vendorCommandServices = $vendorCommandServices;
        $this->vendorDatatableServices = $vendorDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.vendor.index');
    }

    public function datatable(Request $request)
    {
        return $this->vendorDatatableServices->datatable($request);
    }

    public function store(VendorStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $vendor = $this->vendorCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data Vendor',
                'data' => $vendor,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $vendor = $this->vendorQueryServices->findById($id);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data Vendor',
            'data' => $vendor,
        ], 200);
    }

    public function update(VendorUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $vendor = $this->vendorCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data Vendor',
                'data' => $vendor,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $vendor = $this->vendorCommandServices->destroy($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data Vendor',
                'data' => $vendor,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getDataSelect2(Request $request)
    {
        try {
            $data = $this->vendorQueryServices->getDataSelect2($request);
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
}
