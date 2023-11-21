<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Lokasi\LokasiQueryServices;
use App\Services\Lokasi\LokasiCommandServices;
use App\Http\Requests\Lokasi\LokasiStoreRequest;
use App\Services\Lokasi\LokasiDatatableServices;
use App\Http\Requests\Lokasi\LokasiUpdateRequest;

class LokasiController extends Controller
{
    protected $lokasiCommandServices;
    protected $lokasiQueryServices;
    protected $lokasiDatatableServices;

    public function __construct(
        LokasiCommandServices $lokasiCommandServices,
        LokasiQueryServices $lokasiQueryServices,
        LokasiDatatableServices $lokasiDatatableServices
    ) {
        $this->lokasiCommandServices = $lokasiCommandServices;
        $this->lokasiQueryServices = $lokasiQueryServices;
        $this->lokasiDatatableServices = $lokasiDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.lokasi.index');
    }

    public function store(LokasiStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $lokasi = $this->lokasiCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data Lokasi',
                'data' => $lokasi,
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
            $lokasi = $this->lokasiQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data Lokasi',
                'data' => $lokasi,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(LokasiUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $lokasi = $this->lokasiCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data Lokasi',
                'data' => $lokasi,
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

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $lokasi = $this->lokasiCommandServices->destroy($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data Lokasi',
                'data' => $lokasi,
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

    public function datatable(Request $request)
    {
        return $this->lokasiDatatableServices->datatable($request);
    }

    public function getNodeTree(Request $request)
    {
        try {
            $lokasi = $this->lokasiQueryServices->findByParentId($request->id_parent_lokasi);
            $dataTree = [
                'id' => 'root',
                'text' => 'Universitas Pertamina',
                'children' => $lokasi,
            ];
            return response()->json($dataTree);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getSelect2(Request $request)
    {
        try {
            $lokasi = $this->lokasiQueryServices->generateSelect2($request);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data Lokasi',
                'data' => $lokasi,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getAllSelect2(Request $request)
    {
        try {
            $lokasi = $this->lokasiQueryServices->generateAllSelect2($request);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data Lokasi',
                'data' => $lokasi,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
