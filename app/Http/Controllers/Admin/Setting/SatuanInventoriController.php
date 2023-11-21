<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\SatuanInventori\SatuanInventoriQueryServices;
use App\Services\SatuanInventori\SatuanInventoriCommandServices;
use App\Http\Requests\SatuanInventori\SatuanInventoriStoreRequest;
use App\Services\SatuanInventori\SatuanInventoriDatatableServices;
use App\Http\Requests\SatuanInventori\SatuanInventoriUpdateRequest;

class SatuanInventoriController extends Controller
{
    protected $satuanInventoriCommandServices;
    protected $satuanInventoriQueryServices;
    protected $satuanInventoriDatatableServices;

    public function __construct(
        SatuanInventoriCommandServices $satuanInventoriCommandServices,
        SatuanInventoriQueryServices $satuanInventoriQueryServices,
        SatuanInventoriDatatableServices $satuanInventoriDatatableServices
    ) {
        $this->satuanInventoriCommandServices = $satuanInventoriCommandServices;
        $this->satuanInventoriQueryServices = $satuanInventoriQueryServices;
        $this->satuanInventoriDatatableServices = $satuanInventoriDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.satuan-inventori.index');
    }

    public function datatable(Request $request)
    {
        return $this->satuanInventoriDatatableServices->datatable($request);
    }

    public function store(SatuanInventoriStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $satuan_inventori = $this->satuanInventoriCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data satuan inventori',
                'data' => $satuan_inventori,
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

    public function edit(string $id)
    {
        try {
            $satuan_inventori = $this->satuanInventoriQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data satuan inventori',
                'data' => $satuan_inventori,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(SatuanInventoriUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $satuan_inventori = $this->satuanInventoriCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data satuan inventori',
                'data' => $satuan_inventori,
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
            $satuan_inventori = $this->satuanInventoriCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data satuan inventori',
                'data' => $satuan_inventori,
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
            $data = $this->satuanInventoriQueryServices->getDataSelect2($request);
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
