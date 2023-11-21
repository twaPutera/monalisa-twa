<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Approval\ApprovalQueryServices;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\Pengaduan\PengaduanQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Services\Notification\NotificationQueryServices;
use App\Services\Notification\NotificationCommandServices;

class DashboardController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetServiceQueryServices;
    protected $approvalQueryServices;
    protected $pengaduanQueryServices;
    protected $notificationQueryServices;
    protected $notificationCommandServices;

    public function __construct(
        AssetDataQueryServices $assetDataQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        ApprovalQueryServices $approvalQueryServices,
        PengaduanQueryServices $pengaduanQueryServices,
        NotificationQueryServices $notificationQueryServices,
        NotificationCommandServices $notificationCommandServices
    ) {
        $this->approvalQueryServices = $approvalQueryServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->pengaduanQueryServices = $pengaduanQueryServices;
        $this->notificationQueryServices = $notificationQueryServices;
        $this->notificationCommandServices = $notificationCommandServices;
    }

    public function index()
    {
        return view('pages.admin.dashboard.index');
    }

    public function getSummaryDashboard(Request $request)
    {
        try {
            $countAsset = $this->assetDataQueryServices->countAsset($request);
            $lastUpdateAsset = $this->assetDataQueryServices->lastUpdateAsset($request);
            $nilai_asset = $this->assetDataQueryServices->getValueAsset($request);
            $data_summary_chart_asset = $this->assetDataQueryServices->getDataChartSummaryAssetByGroup($request);
            $data_summary_chart_asset_by_kondisi = $this->assetDataQueryServices->getDataChartSummaryAssetByStatus($request);
            $data_summary_chart_asset_by_month_regis = $this->assetDataQueryServices->getDataChartSummaryAssetByMonthRegister($request);
            $data_summary_service_by_status = $this->assetServiceQueryServices->getDataChartByStatus($request);
            $data_pengaduan = $this->pengaduanQueryServices->findAll($request);
            $data_belum_ditangani = $data_pengaduan->where('status_pengaduan', 'dilaporkan')->count();
            $data_sudah_ditangani = $data_pengaduan->where('status_pengaduan', '!=', 'dilaporkan')->count();
            $data_total_pengaduan = $data_pengaduan->count();
            $data_nilai_buku_asset = $this->assetDataQueryServices->getDataChartNilaiBukuByGroup($request);
            $data_perolehan = $this->assetDataQueryServices->getDataChartNilaiPerolehanByGroup($request);
            return response()->json([
                'success' => true,
                'data' => [
                    'countAsset' => number_format($countAsset, 0, ',', '.'),
                    'lastUpdateAsset' => $lastUpdateAsset,
                    'nilaiAsset' => $nilai_asset,
                    'dataSummaryChartAsset' => $data_summary_chart_asset,
                    'dataSummaryChartAssetByKondisi' => $data_summary_chart_asset_by_kondisi,
                    'dataSummaryChartAssetByMonthRegis' => $data_summary_chart_asset_by_month_regis,
                    'dataSummaryServiceByStatus' => $data_summary_service_by_status,
                    'dataTotalPengaduan' => $data_total_pengaduan,
                    'dataBelumDitangani' => $data_belum_ditangani,
                    'dataSudahDitangani' => $data_sudah_ditangani,
                    'dataNilaiBukuAsset' => $data_nilai_buku_asset,
                    'dataNilaiPerolehan' => $data_perolehan,
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

    public function getDaftarApproval(Request $request)
    {
        try {
            $data = $this->approvalQueryServices->approvalSummaryDashboard($request);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function countNotification(Request $request)
    {
        try {
            $count = $this->notificationQueryServices->countNotificationUser($request->user_id);
            return response()->json([
                'success' => true,
                'data' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getNotificationData(Request $request)
    {
        try {
            $data = $this->notificationQueryServices->findNotificationUser($request);
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

    public function readNotification(Request $request)
    {
        try {
            $data = $this->notificationCommandServices->readNotification($request->id);
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
}
