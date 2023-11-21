<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\InventarisData\InventarisDataQueryServices;
use App\Services\InventarisData\InventarisDataCommandServices;
use App\Http\Requests\InventarisData\UserRequestInventoriStoreRequest;
use App\Http\Requests\InventarisData\UserRequestInventoriUpdateRequest;

class BahanHabisPakaiController extends Controller
{
    protected $inventarisDataQueryServices;
    protected $inventarisDataCommandServices;

    public function __construct(
        InventarisDataQueryServices $inventarisDataQueryServices,
        InventarisDataCommandServices $inventarisDataCommandServices
    ) {
        $this->inventarisDataQueryServices = $inventarisDataQueryServices;
        $this->inventarisDataCommandServices = $inventarisDataCommandServices;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }
    public function index()
    {
        return view('pages.user.asset.bahan-habis-pakai.index');
    }
    public function create()
    {
        return view('pages.user.asset.bahan-habis-pakai.create');
    }

    public function getAllData(Request $request)
    {
        try {
            $request_data = $this->inventarisDataQueryServices->findAllRequest($request)->map(function ($item) {
                $item->link_detail = route('user.asset-data.bahan-habis-pakai.detail', $item->id);
                $item->tanggal_permintaan = date('d/m/Y', strtotime($item->created_at));
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data request bahan habis pakai berhasil didapatkan',
                'data' => $request_data,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Data request bahan habis pakai gagal didapatkan',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $edit = $this->inventarisDataQueryServices->findRequestById($id);
        if ($edit->status != 'ditolak') {
            abort(404);
        }
        return view('pages.user.asset.bahan-habis-pakai.edit', compact('edit'));
    }

    public function detail(string $id)
    {
        try {
            $inventaris = $this->inventarisDataQueryServices->findRequestById($id);
            $name = '-';
            if (config('app.sso_siska')) {
                $user = $inventaris->guid_pengaju == null ? null : $this->userSsoQueryServices->getUserByGuid($inventaris->guid_pengaju);
                $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            } else {
                $user = $inventaris->guid_pengaju == null ? null : $this->userQueryServices->findById($inventaris->guid_pengaju);
                $name = isset($user) ? $user->name : 'Not Found';
            }
            $inventaris->created_by = $name;
            return response()->json([
                'success' => true,
                'message' => 'Data request bahan habis pakai berhasil didapatkan',
                'data' => $inventaris,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Data request bahan habis pakai gagal didapatkan',
            ]);
        }
    }

    public function getAllLogData(Request $request)
    {
        try {
            $pengaduan = $this->inventarisDataQueryServices->findAllLog($request)->map(function ($item) {
                $item->tanggal_log = date('d/m/Y', strtotime($item->created_at));
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data request bahan habis pakai berhasil didapatkan',
                'data' => $pengaduan,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Data request bahan habis pakai gagal didapatkan',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function store(UserRequestInventoriStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $peminjaman = $this->inventarisDataCommandServices->storeFromUser($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Permintaan bahan habis pakai berhasil dibuat',
                'data' => $peminjaman,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Permintaan bahan habis pakai gagal dibuat',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(UserRequestInventoriUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $peminjaman = $this->inventarisDataCommandServices->updateFromUser($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Permintaan bahan habis pakai berhasil diperbaharui',
                'data' => $peminjaman,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Permintaan bahan habis pakai gagal diperbaharui',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
