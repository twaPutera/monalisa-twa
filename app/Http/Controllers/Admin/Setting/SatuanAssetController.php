<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\SatuanAsset\SatuanAssetQueryServices;
use App\Services\SatuanAsset\SatuanAssetCommandServices;
use App\Http\Requests\SatuanAsset\SatuanAssetStoreRequest;
use App\Services\SatuanAsset\SatuanAssetDatatableServices;
use App\Http\Requests\SatuanAsset\SatuanAssetUpdateRequest;

class SatuanAssetController extends Controller
{
    protected $satuanAssetCommandServices;
    protected $satuanAssetQueryServices;
    protected $satuanAssetDatatableServices;

    public function __construct(
        SatuanAssetCommandServices $satuanAssetCommandServices,
        SatuanAssetQueryServices $satuanAssetQueryServices,
        SatuanAssetDatatableServices $satuanAssetDatatableServices
    ) {
        $this->satuanAssetCommandServices = $satuanAssetCommandServices;
        $this->satuanAssetQueryServices = $satuanAssetQueryServices;
        $this->satuanAssetDatatableServices = $satuanAssetDatatableServices;
    }
    public function index()
    {
        return view('pages.admin.settings.satuan-asset.index');
    }

    public function datatable(Request $request)
    {
        return $this->satuanAssetDatatableServices->datatable($request);
    }

    public function store(SatuanAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $satuan_asset = $this->satuanAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data satuan asset',
                'data' => $satuan_asset,
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
            $satuan_asset = $this->satuanAssetQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data satuan asset',
                'data' => $satuan_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(SatuanAssetUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $satuan_asset = $this->satuanAssetCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data satuan asset',
                'data' => $satuan_asset,
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
            $satuan_asset = $this->satuanAssetCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data satuan asset',
                'data' => $satuan_asset,
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
            $data = $this->satuanAssetQueryServices->getDataSelect2($request);
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
