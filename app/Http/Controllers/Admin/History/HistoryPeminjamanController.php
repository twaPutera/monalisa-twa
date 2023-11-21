<?php

namespace App\Http\Controllers\Admin\History;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PeminjamanAssetExport;
use App\Services\PeminjamanAsset\PeminjamanAssetDatatableServices;

class HistoryPeminjamanController extends Controller
{
    protected $peminjamanAssetDatatableServices;

    public function __construct(PeminjamanAssetDatatableServices $peminjamanAssetDatatableServices)
    {
        $this->peminjamanAssetDatatableServices = $peminjamanAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.peminjaman.index');
    }

    public function datatable(Request $request)
    {
        return $this->peminjamanAssetDatatableServices->logPeminjamanDatatable($request);
    }

    public function export(Request $request)
    {
        return (new PeminjamanAssetExport($request->start_date ?? date('Y-m-d'), $request->end_date ?? date('Y-m-d')))->download('laporan-peminjaman.xlsx');
    }
}
