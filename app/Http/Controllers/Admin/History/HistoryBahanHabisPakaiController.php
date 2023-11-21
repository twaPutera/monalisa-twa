<?php

namespace App\Http\Controllers\Admin\History;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequestBahanHabisPakaiExport;
use App\Services\InventarisData\InventarisDataDatatableServices;

class HistoryBahanHabisPakaiController extends Controller
{
    protected $inventarisDataDatatableServices;
    public function __construct(InventarisDataDatatableServices $inventarisDataDatatableServices)
    {
        $this->inventarisDataDatatableServices = $inventarisDataDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.bahan-habis-pakai.index');
    }

    public function datatable(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatableHistory($request);
    }

    public function download(Request $request)
    {
        return Excel::download(new RequestBahanHabisPakaiExport(
            $request->tgl_awal_permintaan,
            $request->tgl_akhir_permintaan,
            $request->tgl_awal_pengambilan,
            $request->tgl_akhir_pengambilan,
            $request->status_permintaan
        ), 'laporan-history-permintaan-bahan-habis-pakai.xlsx');
    }
}
