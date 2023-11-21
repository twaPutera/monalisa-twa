<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\KategoriInventori\KategoriInventoriQueryServices;
use App\Services\KategoriInventori\KategoriInventoriCommandServices;
use App\Http\Requests\KategoriInventori\KategoriInventoriStoreRequest;
use App\Services\KategoriInventori\KategoriInventoriDatatableServices;
use App\Http\Requests\KategoriInventori\KategoriInventoriUpdateRequest;

class KategoriInventoriController extends Controller
{
    protected $kategoriInventoriCommandServices;
    protected $kategoriInventoriQueryServices;
    protected $kategoriInventoriDatatableServices;

    public function __construct(
        KategoriInventoriCommandServices $kategoriInventoriCommandServices,
        KategoriInventoriQueryServices $kategoriInventoriQueryServices,
        KategoriInventoriDatatableServices $kategoriInventoriDatatableServices
    ) {
        $this->kategoriInventoriCommandServices = $kategoriInventoriCommandServices;
        $this->kategoriInventoriQueryServices = $kategoriInventoriQueryServices;
        $this->kategoriInventoriDatatableServices = $kategoriInventoriDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.kategori-inventori.index');
    }

    public function datatable(Request $request)
    {
        return $this->kategoriInventoriDatatableServices->datatable($request);
    }

    public function store(KategoriInventoriStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $kategori_inventori = $this->kategoriInventoriCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data kategori inventori',
                'data' => $kategori_inventori,
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
            $kategori_inventori = $this->kategoriInventoriQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data kategori inventori',
                'data' => $kategori_inventori,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(KategoriInventoriUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $kategori_inventori = $this->kategoriInventoriCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data kategori inventori',
                'data' => $kategori_inventori,
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
            $kategori_inventori = $this->kategoriInventoriCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data kategori inventori',
                'data' => $kategori_inventori,
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
            $data = $this->kategoriInventoriQueryServices->getDataSelect2($request);
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
