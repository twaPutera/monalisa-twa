<?php

namespace App\Http\Controllers\Admin\Keluhan;

use Throwable;
use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Keluhan\KeluhanQueryServices;
use App\Services\Keluhan\KeluhanCommandServices;
use App\Services\Keluhan\KeluhanDatatableServices;
use App\Http\Requests\Keluhan\KeluhanUpdateRequest;

class KeluhanController extends Controller
{
    protected $keluhanDatatableServices;
    protected $keluhanQueryServices;
    protected $keluhanCommandServices;
    public function __construct(
        KeluhanDatatableServices $keluhanDatatableServices,
        KeluhanQueryServices $keluhanQueryServices,
        KeluhanCommandServices $keluhanCommandServices
    ) {
        $this->keluhanDatatableServices = $keluhanDatatableServices;
        $this->keluhanQueryServices = $keluhanQueryServices;
        $this->keluhanCommandServices = $keluhanCommandServices;
    }
    public function index()
    {
        return view('pages.admin.keluhan.index');
    }

    public function datatable(Request $request)
    {
        return $this->keluhanDatatableServices->datatable($request);
    }

    public function datatableLog(Request $request)
    {
        return $this->keluhanDatatableServices->datatableLog($request);
    }

    public function detail(string $id)
    {
        try {
            $listing_keluhan = $this->keluhanQueryServices->findById($id);
            return view('pages.admin.keluhan.components.data._data_modal_detail', compact('listing_keluhan'));
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function getImg(string $id)
    {
        try {
            $keluhan = $this->keluhanQueryServices->findById($id);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data image',
                'data' => $keluhan,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function previewImg(Request $request)
    {
        try {
            $path = storage_path('app/images/asset-pengaduan/' . $request->filename);
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

    public function edit(string $id)
    {
        try {
            $keluhan = $this->keluhanQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data keluhan',
                'data' => $keluhan,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(KeluhanUpdateRequest $request, string $id)
    {
        try {
            $keluhan = $this->keluhanCommandServices->update($request, $id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status pengaduan',
                'data' => $keluhan,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
