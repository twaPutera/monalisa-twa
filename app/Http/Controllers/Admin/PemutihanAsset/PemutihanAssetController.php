<?php

namespace App\Http\Controllers\Admin\PemutihanAsset;

use Throwable;
use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\PemutihanAsset\PemutihanAssetQueryServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use App\Services\PemutihanAsset\PemutihanAssetCommandServices;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreRequest;
use App\Services\PemutihanAsset\PemutihanAssetDatatableServices;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreDetailRequest;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateListingRequest;

class PemutihanAssetController extends Controller
{
    protected $pemutihanAssetDatatableServices;
    protected $assetDataQueryServices;
    protected $pemutihanAssetCommandServices;
    protected $pemutihanAssetQueryServices;
    protected $peminjamanAssetQueryServices;
    public function __construct(
        PemutihanAssetQueryServices $pemutihanAssetQueryServices,
        PemutihanAssetCommandServices $pemutihanAssetCommandServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices,
        PemutihanAssetDatatableServices $pemutihanAssetDatatableServices,
        AssetDataQueryServices $assetDataQueryServices
    ) {
        $this->pemutihanAssetDatatableServices = $pemutihanAssetDatatableServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->pemutihanAssetCommandServices = $pemutihanAssetCommandServices;
        $this->pemutihanAssetQueryServices = $pemutihanAssetQueryServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
    }
    public function index()
    {
        $total_asset = $this->pemutihanAssetQueryServices->findAll()->count();
        return view('pages.admin.pemutihan-asset.bast.index', compact('total_asset'));
    }

    public function datatable(Request $request)
    {
        return $this->pemutihanAssetDatatableServices->datatable($request);
    }

    public function show($id)
    {
        try {
            $data = $this->pemutihanAssetQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function datatableAsset(Request $request)
    {
        return $this->pemutihanAssetDatatableServices->datatableAsset($request);
    }

    public function datatableDetail(Request $request)
    {
        return $this->pemutihanAssetDatatableServices->datatableDetail($request);
    }

    public function store(PemutihanAssetStoreRequest $request)
    {
        try {
            for ($i = 0; $i < count($request->id_checkbox); $i++) {
                $id_checkbox = $request->id_checkbox[$i];
                $findAssetWhere = $this->assetDataQueryServices->findById($id_checkbox, ['peminjaman']);
                if ($findAssetWhere->is_pemutihan == 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terdapat item asset yang sudah dalam penghapusan asset sebelumnya',
                    ], 500);
                    break;
                }
                $findAssetIsPinjam = $this->peminjamanAssetQueryServices->findByIdAsset($id_checkbox);
                if ($findAssetIsPinjam != null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terdapat item asset yang masih dipinjam',
                    ], 500);
                    break;
                }
            }
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data penghapusan asset',
                'data' => $pemutihan,
                'redirect' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function storeDetailUpdate(PemutihanAssetStoreDetailRequest $request, string $id)
    {
        try {
            if ($request->hasFile('gambar_asset')) {
                foreach ($request->file('gambar_asset') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $allowedfileExtension = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
                    if (! in_array($extension, $allowedfileExtension)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Terdapat file yang tidak sesuai dengan format',
                        ], 500);
                        break;
                    }
                }
            }
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->storeDetailUpdate($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data penghapusan asset',
                'data' => $pemutihan,
                'redirect' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function downloadBeritaAcara(Request $request)
    {
        try {
            $path = storage_path('app/file/pemutihan/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::downloadFile($path, $filename);
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function storeDetail(string $id)
    {
        $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id, 'Draft');
        if ($pemutihan_asset->is_store == 0) {
            return view('pages.admin.pemutihan-asset.components.page.detail', compact('pemutihan_asset'));
        }
        return abort(404);
    }

    public function storeDetailCancel(string $id)
    {
        try {
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->destroy($id);
            DB::commit();
            return redirect()->route('admin.pemutihan-asset.index');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.pemutihan-asset.index');
        }
    }
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->destroy($id);
            DB::commit();
            if ($pemutihan) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data penghapusan asset',
                    'data' => $pemutihan,
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data penghapusan asset',
            ], 500);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id, 'Draft');
            return view('pages.admin.pemutihan-asset.components.page.edit', compact('pemutihan_asset'));
        } catch (Throwable $th) {
            return redirect()->route('admin.pemutihan-asset.index');
        }
    }

    public function editDitolak(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id, 'Ditolak');
            return view('pages.admin.pemutihan-asset.components.page.edit', compact('pemutihan_asset'));
        } catch (Throwable $th) {
            return redirect()->route('admin.pemutihan-asset.index');
        }
    }

    public function editListingAsset(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id, 'Draft');
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data penghapusan asset',
                'data' => $pemutihan_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function editListingAssetGetImg(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findDetailById($id);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data image',
                'data' => $pemutihan_asset,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function editListingAssetShowImg(Request $request)
    {
        try {
            $path = storage_path('app/images/asset-pemutihan/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::viewFile($path, $filename);

            return $response;
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function detail(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data detail penghapusan asset',
                'data' => $pemutihan_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function editListingAssetUpdate(PemutihanAssetUpdateListingRequest $request, string $id)
    {
        try {
            for ($i = 0; $i < count($request->id_checkbox); $i++) {
                $id_checkbox = $request->id_checkbox[$i];
                $findAssetWhere = $this->assetDataQueryServices->findById($id_checkbox);
                if ($findAssetWhere->is_pemutihan == 1) {
                    break;
                    return response()->json([
                        'success' => false,
                        'message' => 'Terdapat item asset yang sudah dalam penghapusan asset sebelumnya',
                    ], 500);
                }
            }
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->updateListingPemutihan($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data penghapusan asset',
                'data' => $pemutihan,
                'reload' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(PemutihanAssetUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data penghapusan asset',
                'data' => $pemutihan,
                'redirect' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
