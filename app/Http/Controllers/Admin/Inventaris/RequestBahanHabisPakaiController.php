<?php

namespace App\Http\Controllers\Admin\Inventaris;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\InventarisData\InventarisDataQueryServices;
use App\Services\InventarisData\InventarisDataCommandServices;
use App\Services\InventarisData\InventarisDataDatatableServices;
use App\Http\Requests\InventarisData\InventarisDataRealisasiRequest;

class RequestBahanHabisPakaiController extends Controller
{
    protected $inventarisDataDatatableServices;
    protected $inventarisDataQueryServices;
    protected $inventarisDataCommandServices;
    protected $userSsoQueryServices;
    protected $userQueryServices;
    public function __construct(
        InventarisDataCommandServices $inventarisDataCommandServices,
        InventarisDataDatatableServices $inventarisDataDatatableServices,
        InventarisDataQueryServices $inventarisDataQueryServices
    ) {
        $this->inventarisDataDatatableServices = $inventarisDataDatatableServices;
        $this->inventarisDataQueryServices = $inventarisDataQueryServices;
        $this->inventarisDataCommandServices = $inventarisDataCommandServices;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function index()
    {
        return view('pages.admin.listing-inventaris.permintaan.index');
    }

    public function datatable(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatablePermintaan($request);
    }

    public function datatableLog(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatableLogPermintaan($request);
    }

    public function realisasi(string $id)
    {
        $permintaan = $this->inventarisDataQueryServices->findPermintaan($id);
        $name = 'Not Found';
        if (config('app.sso_siska')) {
            $user = $permintaan->guid_pengaju == null ? null : $this->userSsoQueryServices->getUserByGuid($permintaan->guid_pengaju);
            $name = isset($user[0]) ? collect($user[0]) : null;
        } else {
            $user = $this->userQueryServices->findById($permintaan->guid_pengaju);
            $name = isset($user) ? $user->name : 'Not Found';
        }
        $permintaan->pengaju = $name;
        $permintaan->tanggal_permintaan = Carbon::parse($permintaan->created_at)->format('Y-m-d');
        if ($permintaan->status == 'ditolak') {
            abort(404);
        }
        return view('pages.admin.listing-inventaris.permintaan.detail', compact('permintaan'));
    }

    public function storeRealisasi(InventarisDataRealisasiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->inventarisDataCommandServices->storeRealisasi($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil realisasi permintaan',
                'data' => $listing_inventaris,
                'redirect' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
