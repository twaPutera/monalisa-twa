<?php

namespace App\Http\Controllers\Admin\PeminjamanAsset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use App\Services\PeminjamanAsset\PeminjamanAssetCommandServices;
use App\Services\PeminjamanAsset\PeminjamanAssetDatatableServices;
use App\Http\Requests\PeminjamanAsset\DetailPeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetChangeStatusRequest;

class PeminjamanAssetController extends Controller
{
    protected $peminjamanAssetCommandServices;
    protected $peminjamanAssetQueryServices;
    protected $peminjamanAssetDatatableServices;

    public function __construct(
        PeminjamanAssetCommandServices $peminjamanAssetCommandServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices,
        PeminjamanAssetDatatableServices $peminjamanAssetDatatableServices
    ) {
        $this->peminjamanAssetCommandServices = $peminjamanAssetCommandServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
        $this->peminjamanAssetDatatableServices = $peminjamanAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.peminjaman-asset.index');
    }

    public function detail($id)
    {
        $peminjaman = $this->peminjamanAssetQueryServices->findById($id);
        $peminjam = json_decode($peminjaman->json_peminjam_asset);
        return view('pages.admin.peminjaman-asset.detail', compact('peminjaman', 'peminjam'));
    }

    public function show($id)
    {
        try {
            $data = $this->peminjamanAssetQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function getDataPeminjamSelect2(Request $request)
    {
        try {
            $data = $this->peminjamanAssetQueryServices->getDataPeminjamSelect2($request);
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

    public function changeStatus(PeminjamanAssetChangeStatusRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $this->peminjamanAssetCommandServices->changeStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status peminjaman asset',
                'data' => [
                    'command' => 'changeStatus',
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function datatable(Request $request)
    {
        return $this->peminjamanAssetDatatableServices->datatable($request);
    }

    public function detailPeminjamanDatatable(Request $request)
    {
        return $this->peminjamanAssetDatatableServices->detailPeminjamanDatatable($request);
    }

    public function storeManyDetailPeminjaman(DetailPeminjamanAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->peminjamanAssetCommandServices->storeManyDetailPeminjaman($request);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data',
                'data' => [
                    'command' => 'storeManyDetailPeminjaman',
                    'quota' => $data,
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function deleteDetailPeminjaman($id)
    {
        try {
            $data = $this->peminjamanAssetCommandServices->deleteDetailPeminjaman($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data',
                'data' => [
                    'command' => 'deleteDetailPeminjaman',
                    'quota' => $data,
                ],
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
