<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use Throwable;
use App\Models\ZipFile;
use App\Models\AssetData;
use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use App\Imports\DataAssetImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterDataAssetExport;
use App\Helpers\StatusAssetDataHelpers;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Services\AssetData\AssetDataQueryServices;
use App\Http\Requests\AssetData\AssetImportRequest;
use App\Http\Requests\AssetData\AssetUpdateRequest;
use App\Services\AssetData\AssetDataCommandServices;
use App\Services\AssetData\AssetDataDatatableServices;
use App\Services\AssetOpname\AssetOpnameQueryServices;
use App\Http\Requests\AssetData\AssetDataDeleteRequest;
use App\Http\Requests\AssetData\AssetDataPublishRequest;
use App\Http\Requests\AssetData\AssetUpdateDraftRequest;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Models\Lokasi;
use App\Models\SistemConfig;

class MasterAssetController extends Controller
{
    protected $assetDataCommandServices;
    protected $assetDataDatatableServices;
    protected $assetDataQueryServices;
    protected $userSsoQueryServices;
    protected $assetServiceQueryServices;
    protected $assetOpnameQueryServices;
    protected $userQueryServices;

    public function __construct(
        AssetDataCommandServices $assetDataCommandServices,
        AssetDataDatatableServices $assetDataDatatableServices,
        AssetDataQueryServices $assetDataQueryServices,
        UserSsoQueryServices $userSsoQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        AssetOpnameQueryServices $assetOpnameQueryServices,
        UserQueryServices $userQueryServices
    ) {
        $this->assetDataCommandServices = $assetDataCommandServices;
        $this->assetDataDatatableServices = $assetDataDatatableServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->userSsoQueryServices = $userSsoQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->assetOpnameQueryServices = $assetOpnameQueryServices;
        $this->userQueryServices = $userQueryServices;
    }

    public function index()
    {
        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        return view('pages.admin.listing-asset.index', compact('list_status'));
    }

    public function indexDraft()
    {
        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        return view('pages.admin.listing-asset.draft-asset.index', compact('list_status'));
    }

    public function datatable(Request $request)
    {
        return $this->assetDataDatatableServices->datatable($request);
    }

    public function datatableReport(Request $request)
    {
        return $this->assetDataDatatableServices->datatableReport($request);
    }

    public function store(AssetStoreRequest $request)
    {
        //dd($request);
        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data asset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $array_search = ['peminjaman' => true];
            $data = $this->assetDataQueryServices->findById($id, $array_search);
            $service = $this->assetServiceQueryServices->findLastestLogByAssetId($id);
            // dd($data);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data asset',
                'data' => [
                    'asset' => $data,
                    'service' => $service,
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function detail($id)
    {
        $asset = $this->assetDataQueryServices->findById($id);
        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        return view('pages.admin.listing-asset.detail', compact('asset', 'list_status'));
    }

    public function update(AssetUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data asset',
                'form' => 'editAsset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateDraft(AssetUpdateDraftRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->updateDraft($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data asset',
                'form' => 'editAsset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function publishManyAsset(AssetDataPublishRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->publishAssetMany($request);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mempublikasikan data asset',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function deleteManyAsset(AssetDataDeleteRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->deleteAssetMany($request);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data asset',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function deleteAllDraftAsset()
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->deleteAllDraftAsset();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data asset',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function publishAllDraftAsset()
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->publishAllDraftAsset();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mempublikasikan data asset',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->assetDataCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data asset',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function putToTrash(string $id)
    {
        DB::beginTransaction();
        try {
            $this->assetDataCommandServices->putToTrash($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus sementara data asset',
                'location' => route('admin.listing-asset.index'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function previewImage(Request $request)
    {
        try {
            $path = storage_path('app/images/asset/' . $request->filename);
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

    public function previewQr(Request $request)
    {
        try {
            $path = storage_path('app/images/qr-code/' . $request->filename);
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

    public function downloadQr(Request $request)
    {
        try {
            $path = storage_path('app/images/qr-code/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::downloadFile($path, $filename);

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

    public function downloadTemplateImport()
    {
        return Excel::download(new MasterDataAssetExport, 'template-import-asset.xlsx');
    }

    public function getDataAllUnitkerjaSelect2(Request $request)
    {
        try {
                //$response = $this->userQueryServices->findAll($request);
                $response=DB::table('unit_kerja')
                ->select('*')
                ->where('flag',1)->get();
                $data = $response->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->unit_kerja,
                    ];
                });

                // Tambahkan data baru ke dalam array $data
                $newData = [
                    'id' => 0, // Gantilah dengan nilai ID yang sesuai
                    'text' => 'Kosongkan',
                ];

                $data->prepend($newData);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menampilkan data unit kerja',
                    'data' => $data,
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

    public function getDataAllOwnerSelect2(Request $request)
    {
        try {
            if (config('app.sso_siska')) {
                $response = $this->userSsoQueryServices->getUserSso($request);
                $data = collect($response)->map(function ($item) {
                    return [
                        'id' => $item['guid'],
                        'text' => $item['name'] . ' - ' . $item['email'],
                    ];
                });
            }else{
                $response = $this->userQueryServices->findAll($request);
                $data = $response->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->name . ' - ' . $item->email,
                    ];
                });
            }

            // $response1=DB::table('unit_kerja')
            //     ->select('*')
            //     ->where('flag',1)->get();
                
            // $newData = $response1->map(function ($item) {
            //         return [
            //             'id' => $item->id,
            //             'text' => $item->unit_kerja,
            //         ];
            // });

            // Tambahkan hasil query sebagai data baru
            // foreach ($response1 as $item) {
            //     $newData = [
            //         'id' => $item->id,
            //         'text' => $item->unit_kerja,
            //     ];
            //     $data->push($newData);
            // }

            // Tambahkan data baru ke dalam array $data
            $newData = [
                'id' => 0, // Gantilah dengan nilai ID yang sesuai
                'text' => 'Kosongkan',
            ];

            $data->prepend($newData);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data owner',
                'data' => $data,
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

    public function getDataAllAssetSelect2(Request $request)
    {
        try {
            $data = $this->assetDataQueryServices->getDataAssetSelect2($request);
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

    


    public function importAssetData(AssetImportRequest $request)
    {
       //dd($request);
        try {
            //dd($request);
            DB::beginTransaction();
            $response = Excel::import(new DataAssetImport(), $request->file('file'));
            DB::commit();
            return response()->json([
                'success' => true,
                'form' => 'import',
                'message' => 'Berhasil mengimport data asset',
                // 'data' => $response,
            ], 200);
            //code...
        } catch (\Maatwebsite\Excel\Validators\ValidationException $th) {
            DB::rollback();
            $failures = $th->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
            return response()->json([
                'success' => false,
                'form' => 'import',
                'message' => $th->getMessage(),
                'errors' => $errors,
            ], 400);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'form' => 'import',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function log_asset_dt(Request $request)
    {
        $response = $this->assetDataDatatableServices->log_asset_dt($request);
        return $response;
    }

    public function image_asset_dt(Request $request)
    {
        $response = $this->assetDataDatatableServices->image_asset_dt($request);
        return $response;
    }

    public function log_opname_dt(Request $request)
    {
        $response = $this->assetDataDatatableServices->log_opname_dt($request);
        return $response;
    }

    public function detail_image_asset_dt(string $id)
    {
        try {
            $data = $this->assetDataQueryServices->findImageById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data image',
                'data' => $data,
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function update_image_asset_dt(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->updateImageAsset($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data image',
                'data' => $data,
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function store_image_asset_dt(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->storeImageAsset($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data image',
                'data' => $data,
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function delete_image_asset_dt(string $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->assetDataCommandServices->deleteImageAsset($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data image',
                'data' => $data,
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function log_opname_show($id)
    {
        try {
            $data = $this->assetOpnameQueryServices->findById($id);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data image',
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

    public function previewImageOpname(Request $request)
    {
        try {
            $path = storage_path('app/images/asset-opname/' . $request->filename);
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

    public function downloadZipQr(Request $request)
    {
        $zipFile = new \PhpZip\ZipFile();
        $filename = 'all-qr-' . time() . '.zip';
        $outputFilename = storage_path('app/images/qr-code/' . $filename);
        try {
            $data = $this->assetDataQueryServices->findAll($request);

            foreach ($data as $key => $value) {
                if (\File::exists(storage_path('app/images/qr-code/' . $value->qr_code))) {
                    $zipFile->addFile(storage_path('app/images/qr-code/' . $value->qr_code), $value->qr_code);
                }
            }

            $zipFile->saveAsFile($outputFilename);
            $zipFile->close();

            $zip = new ZipFile();
            $zip->name = $filename;
            $zip->path = 'app/images/qr-code/' . $filename;
            $zip->save();

            return response()->download($outputFilename);
        } catch (\PhpZip\Exception\ZipException $e) {
            // handle exception
        } finally {
            $zipFile->close();
        }
    }

    public function printAllQr(Request $request)
    {
        $page = 1;
        $limit = 50;

        if (isset($request->page)) {
            $page = $request->page;
        }

        if (isset($request->limit)) {
            $limit = $request->limit;
        }

        $assets = AssetData::query();

        if (isset($request->id)) {
            $assets->where('id', $request->id);
        }

        if (isset($request->deskripsi)) {
            $assets->where(function ($query) use ($request) {
                $query->where('deskripsi', 'like', '%' . $request->deskripsi . '%')
                    ->orWhere('kode_asset', 'like', '%' . $request->deskripsi . '%');
            });
        }

        if (isset($request->id_satuan_asset)) {
            $assets->where('id_satuan_asset', $request->id_satuan_asset);
        }

        if (isset($request->tgl_perolehan_awal) && isset($request->tgl_perolehan_akhir)) {
            $assets->whereBetween('tanggal_perolehan', [$request->tgl_perolehan_awal, $request->tgl_perolehan_akhir]);
        }

        if (isset($request->id_vendor)) {
            $assets->where('id_vendor', $request->id_vendor);
        }

        if (isset($request->id_lokasi) && $request->id_lokasi != 'root') {
            $assets->where('id_lokasi', $request->id_lokasi);
        }

        if (isset($request->id_kelas_asset)) {
            $assets->where('id_kelas_asset', $request->id_kelas_asset);
        }

        if (isset($request->id_kategori_asset)) {
            $assets->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->is_sparepart)) {
            $assets->where('is_sparepart', $request->is_sparepart);
        }

        if (isset($request->is_draft)) {
            $assets->where('is_draft', $request->is_draft);
        }

        $assets->orderBy('kode_asset', 'ASC');

        $assets = $assets->where('is_draft', '0')
            ->where('is_pemutihan', '0')
            ->select('id', 'kode_asset', 'deskripsi', 'qr_code')
            ->paginate($limit);
        return view('pages.admin.listing-asset.print-all-qr', compact('assets'));
    }

    public function getMaxValueNoUrutAssetByKelompokId(string $id, string $id_asset = null)
    {
        try {
            $data = $this->assetDataQueryServices->getMaxValueNoUrutAssetByKelompokId($id, $id_asset);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data',
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

    public function addDraft(){
        $listKelompokAsset = DB::table('group_kategori_assets')->get();

        $satuan = DB::table('satuan_assets')->get();

        $unit_kerja = DB::table('unit_kerja')->get();

        $kelas_assets = DB::table('kelas_assets')->get();

        $ownership = DB::table('users')->get();

        $vendors = DB::table('vendors as a')
            ->join('asset_data as b', 'a.id', '=', 'b.id_vendor')
            ->groupBy('a.id','a.nama_vendor')
            ->select('a.id', 'a.nama_vendor')
            ->get();

        $lokasi = Lokasi::query();
        $lokasi = $lokasi->where('id_parent_lokasi', null)
            ->get();
        foreach ($lokasi as $item) {
            $arraySelect2[] = [
                'id' => $item->id,
                'text' => '- ' . $item->nama_lokasi,
            ];
            $arraySelect2 = array_merge($arraySelect2, $this->getSelect2Children($item->id, 2));
        }

        //dd($arraySelect2[0]['text']);
        // foreach($arraySelect2 as $row){
        //     dd($row["text"]);
        // }

        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        //dd($list_status);


        return view('pages.admin.listing-asset.draft-asset.twa_add_draft')
        ->with('vendors',$vendors)
        ->with('satuan',$satuan)
        ->with('list_status',$list_status)
        ->with('kelas_assets',$kelas_assets)
        ->with('unit_kerja',$unit_kerja)
        ->with('ownership',$ownership)
        ->with('lokasi',$arraySelect2)
        ->with('listKelompokAsset',$listKelompokAsset);
    }

    public function getSelect2Children($id_parent_lokasi, $iterasi)
    {
        $strip = str_repeat('-', $iterasi);
        $arraySelect2 = [];
        $lokasi = Lokasi::query();

        $lokasi = $lokasi->where('id_parent_lokasi', $id_parent_lokasi)->get();
        $iterasi++;
        foreach ($lokasi as $item) {
            $arraySelect2[] = [
                'id' => $item->id,
                'text' => $strip . ' ' . $item->nama_lokasi,
            ];
            $arraySelect2 = array_merge($arraySelect2, $this->getSelect2Children($item->id, $iterasi));
        }
        return $arraySelect2;
    }

    public function getJenisAset($group)
    {
        // Di sini, Anda dapat menggantinya dengan logika database atau logika lainnya
        // untuk mendapatkan jenis aset berdasarkan kelompok yang dipilih

        // Contoh data simulasi
        $data = DB::table('kategori_assets')->where('id_group_kategori_asset', '=', "$group")->get();

        // Kembalikan data dalam format JSON
        return response()->json($data);
    }

    public function getNoUrutTwa($id_kategori_asset){
        $asset =  DB::table('asset_data')
        ->where('id_kategori_asset', $id_kategori_asset)
        ->whereRaw('no_urut REGEXP "^([,|.]?[0-9])+$"')
        ->max('no_urut');

        $no_urut_config = DB::table('sistem_configs')
            ->where('config', 'min_no_urut')
            ->first();

        $config = $no_urut_config->value ?? 5;

        $no = 1;

        if (isset($asset)) {
            $no = $asset + 1;

            // if ($id_asset != null) {
            //     $plus_one = AssetData::where('id', $id_asset)
            //         ->where('id_kategori_asset', $id)
            //         ->where('no_urut', $asset)
            //         ->first();
            //     if ($plus_one) {
            //         $no = $asset;
            //     }
            // }
        }

        $no_urut = str_pad($no, $config, '0', STR_PAD_LEFT);

        return response()->json(['no_urut' => $no_urut]);
    }

    public function store_twa(AssetStoreRequest $request)
    {
        // $min_no_urut = SistemConfig::where('config', 'min_no_urut')->first();
        // $min_no_urut = $min_no_urut->value ?? '5';
        // //dd($request);
        // $validatedData = $request->validate([
        //     'kode_asset' => 'required|string|unique:asset_data,kode_asset|max:255',
        //     'id_vendor' => 'nullable|uuid|exists:vendors,id',
        //     'id_lokasi' => 'nullable|uuid|exists:lokasis,id',
        //     'id_kelas_asset' => 'nullable|uuid|exists:kelas_assets,id',
        //     'id_group_asset' => 'required',
        //     'id_kategori_asset' => 'required|uuid|exists:kategori_assets,id',
        //     'id_satuan_asset' => 'required|uuid|exists:satuan_assets,id',
        //     'deskripsi' => 'required|string|max:255',
        //     'tanggal_perolehan' => 'required|date',
        //     //perubahan oleh wahyu
        //     'tanggal_pelunasan' => 'nullable|date',
        //     //'nilai_perolehan' => 'required|numeric',
        //     'nilai_perolehan' => 'nullable|numeric', //tambahan wahyu
        //     // 'nilai_buku_asset' => 'required|numeric|lte:nilai_perolehan',
        //     'jenis_penerimaan' => 'required|string|max:255|in:PO,Hibah Eksternal,Hibah Penelitian,Hibah Perorangan,UMK,CC,Reimburse',
        //     //'ownership' => 'nullable|uuid',
        //     'ownership' => 'nullable', //tambahan dari wahyu
        //     // 'tgl_register' => 'required|date|date_format:Y-m-d',
        //     // 'register_oleh' => 'required|uuid',
        //     'no_memo_surat' => 'nullable|string|max:50',
        //     'no_memo_surat_manual' => 'nullable|required_if:status_memorandum,manual|string|max:50',
        //     'id_surat_memo_andin' => 'nullable|required_if:status_memorandum,andin|uuid',
        //     'status_memorandum' => 'required|string|in:andin,manual,tidak-ada',
        //     'no_po' => 'nullable|string|max:50',
        //     'no_sp3' => 'nullable|string|max:50',
        //     'status_kondisi' => 'required|string|max:50',
        //     'status_akunting' => 'required|string|max:50',
        //     'no_seri' => 'nullable|string|max:50',
        //     'no_urut' => 'nullable|string|max:50|min:' . $min_no_urut,
        //     'cost_center' => 'nullable|string|max:255',
        //     'call_center' => 'nullable|string|max:50',
        //     'spesifikasi' => 'required|string|max:255',
        //     'status_kondisi' => 'required|string|max:50',
        //     // 'nilai_depresiasi' => 'required|numeric',
        //     // 'umur_manfaat_fisikal' => 'nullable|numeric',
        //     // 'umur_manfaat_komersial' => 'nullable|numeric',
        //     // 'gambar_asset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'gambar_asset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        //     'is_sparepart' => 'nullable|in:0,1',
        //     'is_pinjam' => 'nullable|in:0,1',
        //     'is_it' => 'nullable|in:0,1',
        //     'asal_asset' => 'nullable|string|uuid',
        // ]);

        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->store($request);
            DB::commit();
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Berhasil menambahkan data asset',
            //     'data' => $data,
            // ],202)->header('Location', route('admin.listing-asset.draft.index'));
            return redirect()->route('admin.listing-asset.draft.index');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
        

        // Proses penyimpanan data jika validasi berhasil
        // ...

        //return redirect()->route('admin.listing-asset.draft.index');
    }
}
