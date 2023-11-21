<?php

namespace App\Http\Controllers\Admin\History;

use Illuminate\Http\Request;
use App\Exports\PengaduanExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Keluhan\KeluhanDatatableServices;

class HistoryPengaduanController extends Controller
{
    protected $keluhanDatatableServices;

    public function __construct(KeluhanDatatableServices $keluhanDatatableServices)
    {
        $this->keluhanDatatableServices = $keluhanDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.pengaduan.index');
    }

    public function download(Request $request)
    {
        return Excel::download(new PengaduanExport($request->tgl_awal, $request->tgl_akhir, $request->id_lokasi, $request->id_kategori_asset, $request->id_asset_data, $request->status_pengaduan), 'laporan-history-pengaduan.xlsx');
    }

    public function datatable(Request $request)
    {
        return $this->keluhanDatatableServices->datatableHistoryPengaduan($request);
    }
}
