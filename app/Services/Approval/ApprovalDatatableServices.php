<?php

namespace App\Services\Approval;

use App\Models\Approval;
use Illuminate\Http\Request;
use App\Models\KategoriAsset;
use Yajra\DataTables\DataTables;
use App\Models\DetailPemindahanAsset;

class ApprovalDatatableServices
{
    public function datatable(Request $request)
    {
        $query = Approval::query()
            ->select('approvals.*');

        if (isset($request->guid_approver)) {
            $query->where(function ($query) use ($request) {
                $query->where('guid_approver', $request->guid_approver)
                    ->orWhere('guid_approver', null);
            });
        }

        if (isset($request->approvable_type)) {
            $query->where('approvable_type', $request->approvable_type);
        }

        if (isset($request->approvable_types)) {
            $query->whereIn('approvable_type', $request->approvable_types);
        }

        if ($request->has('is_approve')) {
            $query->where('is_approve', $request->is_approve);
        }

        if ($request->has('is_it')) {
            $query->leftjoin('peminjaman_assets', 'peminjaman_assets.id', '=', 'approvals.approvable_id')
                ->leftjoin('perpanjangan_peminjaman_assets', 'perpanjangan_peminjaman_assets.id', '=', 'approvals.approvable_id')
                ->where(function ($query) use ($request) {
                    $query->where('peminjaman_assets.is_it', $request->is_it)
                        ->orWhere('perpanjangan_peminjaman_assets.is_it', $request->is_it);
                });
        }

        $query->orderBy('approvals.created_at', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('approvable', function ($item) {
                return $item->approvable->toArray();
            })
            ->addColumn('tipe_approval', function ($row) {
                return $row->approvalType();
            })
            ->addColumn('data_detail_approval', function ($item) use ($request) {
                $array_detail = [];
                if ($item->approvable_type == 'App\Models\PemindahanAsset') {
                    $array_detail['penerima_asset'] = json_decode($item->approvable->json_penerima_asset)->nama;
                    $array_detail['penyerah_asset'] = json_decode($item->approvable->json_penyerah_asset)->nama;
                    $detail_pemindahan = DetailPemindahanAsset::query()
                        ->where('id_pemindahan_asset', $item->approvable->id)
                        ->first();
                    $asset = json_decode($detail_pemindahan->json_asset_data);
                    $jenis_asset = KategoriAsset::query()
                        ->where('id', $asset->id_kategori_asset)
                        ->first();
                    $array_detail['nama_asset'] = $asset->deskripsi ?? 'No Data';
                    $array_detail['jenis_asset'] = $jenis_asset->nama_kategori ?? 'No Data';
                    $array_detail['link_detail'] = route('admin.listing-asset.pemindahan-asset.show', $item->approvable->id);
                    $array_detail['link_stream_bast'] = route('admin.listing-asset.pemindahan-asset.print-bast', $item->approvable->id);
                }

                return $array_detail;
            })
            ->addColumn('pembuat_approval', function ($row) {
                return $row->getPembuatApproval();
            })
            ->addColumn('link_detail', function ($item) {
                return $item->linkApproval();
            })
            ->addColumn('link_update', function ($item) {
                return $item->linkUpdateApproval();
            })
            ->rawColumns(['approvable', 'array_detail'])
            ->make(true);
    }
}
