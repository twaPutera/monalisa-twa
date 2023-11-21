<?php

namespace App\Services\Approval;

use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalQueryServices
{
    public function findAll(string $approvable_type)
    {
        $query = Approval::query();
        $query->where('approvable_type', $approvable_type);
        $query->orderBy('created_at', 'ASC')->get();
        return $query;
    }

    public function approvalSummaryDashboard(Request $request)
    {
        $is_it = null;

        if ($request->role == 'manager_it' || $request->role == 'staff_it') {
            $is_it = '1';
        } elseif ($request->role == 'manager_asset' || $request->role == 'staff_asset') {
            $is_it = '0';
        }

        $approval_peminjaman = Approval::query()
            ->join('peminjaman_assets', 'peminjaman_assets.id', '=', 'approvals.approvable_id')
            ->where('approvable_type', 'App\\Models\\PeminjamanAsset')
            ->where('approvals.is_approve', null);

        $approval_perpancangan_peminjaman_asset = Approval::query()
            ->join('perpanjangan_peminjaman_assets', 'perpanjangan_peminjaman_assets.id', '=', 'approvals.approvable_id')
            ->where('approvable_type', 'App\\Models\\PerpanjanganPeminjamanAsset')
            ->where('approvals.is_approve', null);

        $approval_pemindahan_asset = Approval::query()
            ->join('pemindahan_assets', 'pemindahan_assets.id', '=', 'approvals.approvable_id')
            ->join('detail_pemindahan_assets', 'detail_pemindahan_assets.id_pemindahan_asset', '=', 'pemindahan_assets.id')
            ->join('asset_data', 'asset_data.id', '=', 'detail_pemindahan_assets.id_asset')
            ->where('approvable_type', 'App\\Models\\PemindahanAsset')
            ->where('approvals.is_approve', null);

        $approval_pemutihan_asset = Approval::query()
            ->join('pemutihan_assets', 'pemutihan_assets.id', '=', 'approvals.approvable_id')
            ->where('approvable_type', 'App\\Models\\PemutihanAsset')
            ->where('approvals.is_approve', null)
            ->where(function ($query) use ($request) {
                $query->where('guid_approver', $request->user_id);
                if (($request->role == 'manager_it') || ($request->role == 'manager_asset') || ($request->role == 'admin')) {
                    $query->orWhere('guid_approver', null);
                }
            });

        $approval_request_inventori = Approval::query()
            ->join('request_inventories', 'request_inventories.id', '=', 'approvals.approvable_id')
            ->where('approvable_type', 'App\\Models\\RequestInventori')
            ->where('approvals.is_approve', null);

        if (isset($is_it)) {
            $approval_peminjaman->where('peminjaman_assets.is_it', $is_it);
            $approval_perpancangan_peminjaman_asset->where('perpanjangan_peminjaman_assets.is_it', $is_it);
            $approval_pemindahan_asset->where('asset_data.is_it', $is_it);
            $approval_pemutihan_asset->where(function ($query) use ($request) {
                $query->where('pemutihan_assets.is_it', $request->is_it)
                        ->orWhere('pemutihan_assets.is_it', '2');
            });
        }

        $summary_approval = [
            'approval_peminjaman' => $approval_peminjaman->count(),
            'approval_perpancangan_peminjaman_asset' => $approval_perpancangan_peminjaman_asset->count(),
            'approval_pemindahan_asset' => $approval_pemindahan_asset->count(),
            'approval_pemutihan_asset' => $approval_pemutihan_asset->count(),
            'approva_request_inventori' => $approval_request_inventori->count(),
            'total_approval' => $approval_peminjaman->count() + $approval_perpancangan_peminjaman_asset->count() + $approval_pemindahan_asset->count() + $approval_pemutihan_asset->count() + $approval_request_inventori->count(),
        ];

        return $summary_approval;
    }

    public function countByGuidApprover(string $guid_approver)
    {
        $query = Approval::query();
        $query->where('guid_approver', $guid_approver);
        $query->where('is_approve', null);
        return $query->count();
    }
}
