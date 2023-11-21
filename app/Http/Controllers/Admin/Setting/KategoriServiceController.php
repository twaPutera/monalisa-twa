<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\KategoriService\KategoriServiceQueryServices;
use App\Services\KategoriService\KategoriServiceCommandServices;
use App\Http\Requests\KategoriService\KategoriServiceStoreRequest;
use App\Services\KategoriService\KategoriServiceDatatableServices;
use App\Http\Requests\KategoriService\KategoriServiceUpdateRequest;

class KategoriServiceController extends Controller
{
    protected $kategoriServiceCommandServices;
    protected $kategoriServiceQueryServices;
    protected $kategoriServiceDatatableServices;

    public function __construct(
        KategoriServiceCommandServices $kategoriServiceCommandServices,
        KategoriServiceQueryServices $kategoriServiceQueryServices,
        KategoriServiceDatatableServices $kategoriServiceDatatableServices
    ) {
        $this->kategoriServiceCommandServices = $kategoriServiceCommandServices;
        $this->kategoriServiceQueryServices = $kategoriServiceQueryServices;
        $this->kategoriServiceDatatableServices = $kategoriServiceDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.kategori-service.index');
    }

    public function datatable(Request $request)
    {
        return $this->kategoriServiceDatatableServices->datatable($request);
    }

    public function store(KategoriServiceStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $kategori_service = $this->kategoriServiceCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data kategori service',
                'data' => $kategori_service,
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
            $kategori_service = $this->kategoriServiceQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data kategori service',
                'data' => $kategori_service,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(KategoriServiceUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $kategori_service = $this->kategoriServiceCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data kategori service',
                'data' => $kategori_service,
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
            $kategori_service = $this->kategoriServiceCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data kategori service',
                'data' => $kategori_service,
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
            $data = $this->kategoriServiceQueryServices->getDataSelect2($request);
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
