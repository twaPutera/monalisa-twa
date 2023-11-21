<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\KelasAsset\KelasAssetQueryServices;
use App\Services\KelasAsset\KelasAssetCommandServices;
use App\Http\Requests\KelasAsset\KelasAssetStoreRequest;
use App\Services\KelasAsset\KelasAssetDatatableServices;
use App\Http\Requests\KelasAsset\KelasAssetUpdateRequest;

class KelasAssetController extends Controller
{
    protected $kelasAssetCommandServices;
    protected $kelasAssetQueryServices;
    protected $kelasAssetDatatableServices;

    public function __construct(
        KelasAssetCommandServices $kelasAssetCommandServices,
        KelasAssetQueryServices $kelasAssetQueryServices,
        KelasAssetDatatableServices $kelasAssetDatatableServices
    ) {
        $this->kelasAssetCommandServices = $kelasAssetCommandServices;
        $this->kelasAssetQueryServices = $kelasAssetQueryServices;
        $this->kelasAssetDatatableServices = $kelasAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.kelas-asset.index');
    }

    public function datatable(Request $request)
    {
        return $this->kelasAssetDatatableServices->datatable($request);
    }

    public function store(KelasAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $kelas_asset = $this->kelasAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data kelas asset',
                'data' => $kelas_asset,
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
            $kelas_asset = $this->kelasAssetQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data kelas asset',
                'data' => $kelas_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(KelasAssetUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $kelas_asset = $this->kelasAssetCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data kelas asset',
                'data' => $kelas_asset,
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
            $kelas_asset = $this->kelasAssetCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data kelas asset',
                'data' => $kelas_asset,
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
            $data = $this->kelasAssetQueryServices->getDataSelect2($request);
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
