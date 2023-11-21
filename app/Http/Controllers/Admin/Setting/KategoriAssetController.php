<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\KategoriAsset\KategoriAssetQueryServices;
use App\Services\KategoriAsset\KategoriAssetCommandServices;
use App\Http\Requests\KategoriAsset\KategoriAssetStoreRequest;
use App\Services\KategoriAsset\KategoriAssetDatatableServices;
use App\Http\Requests\KategoriAsset\KategoriAssetUpdateRequest;

class KategoriAssetController extends Controller
{
    protected $kategoriAssetCommandServices;
    protected $kategoriAssetQueryServices;
    protected $kategoriAssetDatatableServices;

    public function __construct(
        KategoriAssetCommandServices $kategoriAssetCommandServices,
        KategoriAssetQueryServices $kategoriAssetQueryServices,
        KategoriAssetDatatableServices $kategoriAssetDatatableServices
    ) {
        $this->kategoriAssetCommandServices = $kategoriAssetCommandServices;
        $this->kategoriAssetQueryServices = $kategoriAssetQueryServices;
        $this->kategoriAssetDatatableServices = $kategoriAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.kategori-asset.index');
    }

    public function store(KategoriAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $kategori_asset = $this->kategoriAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data kategori asset',
                'data' => $kategori_asset,
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
            $kategori_asset = $this->kategoriAssetQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data kategori asset',
                'data' => $kategori_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(KategoriAssetUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $kategori_asset = $this->kategoriAssetCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data kategori asset',
                'data' => $kategori_asset,
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
            $kategori_asset = $this->kategoriAssetCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data kategori asset',
                'data' => $kategori_asset,
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
        return $this->kategoriAssetDatatableServices->datatable($request);
    }

    public function getDataSelect2(Request $request)
    {
        try {
            $data = $this->kategoriAssetQueryServices->getDataSelect2($request);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data kategori asset',
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
