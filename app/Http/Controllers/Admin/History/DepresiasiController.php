<?php

namespace App\Http\Controllers\Admin\History;

use Illuminate\Http\Request;
use App\Exports\DepresiasiExport;
use App\Http\Controllers\Controller;
use App\Services\DepresiasiAsset\DepresiasiAssetDatatableServices;

class DepresiasiController extends Controller
{
    protected $depresiasiAssetDatatableServices;

    public function __construct(DepresiasiAssetDatatableServices $depresiasiAssetDatatableServices)
    {
        $this->depresiasiAssetDatatableServices = $depresiasiAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.depresiasi.index');
    }

    public function datatable(Request $request)
    {
        return $this->depresiasiAssetDatatableServices->datatable($request);
    }

    public function download(Request $request)
    {
        return (new DepresiasiExport($request->year ?? date('Y')))->download('laporan-depresiasi-asset-tahun-'. $request->year .'.xlsx');
    }
}
