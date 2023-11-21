<?php

namespace App\Services\PeminjamanAsset;

use Illuminate\Http\Request;
use App\Models\PeminjamanAsset;
use Yajra\DataTables\DataTables;
use App\Models\LogPeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class PeminjamanAssetDatatableServices
{
    protected $userSsoQueryServices;
    protected $userQueryServices;
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function datatable(Request $request)
    {
        $query = PeminjamanAsset::query()
            ->join('users', 'users.id', '=', 'peminjaman_assets.guid_peminjam_asset')
            ->select('peminjaman_assets.*', 'users.name as nama_peminjam')
            ->with(['approval']);

        if (isset($request->status)) {
            $query->where('status', $request->status);
        }

        if (isset($request->guid_peminjam_asset)) {
            $query->where('guid_peminjam_asset', $request->guid_peminjam_asset);
        }

        if (isset($request->id_asset_data)) {
            $query->whereHas('detail_peminjaman_asset', function ($query) use ($request) {
                $query->where('id_asset', $request->id_asset_data);
            });
        }

        if (isset($request->tanggal_awal)) {
            $query->where('tanggal_peminjaman', '>=', $request->tanggal_awal);
        }

        if (isset($request->tanggal_akhir)) {
            $query->where('tanggal_pengembalian', '<=', $request->tanggal_akhir);
        }

        if (isset($request->keyword)) {
            $query->where(function ($query) use ($request) {
                $query->where('code', 'LIKE', '%' . $request->keyword . '%');
                $query->orWhere('users.name', 'LIKE', '%' . $request->keyword . '%');
            });
        }

        if (isset($request->status_peminjaman)) {
            if ($request->status_peminjaman != 'all') {
                $query->where('status', $request->status_peminjaman);
            }
        }

        if (isset($request->status_approval)) {
            if ($request->status_approval != 'all') {
                $query->whereHas('approval', function ($query) use ($request) {
                    $query->where('is_approve', $request->status_approval);
                });
            }

            if ($request->status_approval == 'other') {
                $query->whereHas('approval', function ($query) use ($request) {
                    $query->where('is_approve', null);
                });
            }
        }

        $query->orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            // ->addColumn('nama_peminjam', function ($row) {
            //     $peminjam = json_decode($row->json_peminjam_asset);
            //     return $peminjam->name;
            // })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<a href="' . route('admin.peminjaman.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                <i class="fa fa-eye"></i>
                            </a>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detailPeminjamanDatatable(Request $request)
    {
        $query = DetailPeminjamanAsset::query();

        if (isset($request->id_peminjaman_asset)) {
            $query->where('id_peminjaman_asset', $request->id_peminjaman_asset);
        }

        if (isset($request->id_asset)) {
            $query->where('id_asset', $request->id_asset);
        }

        $query->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('asset_data', function ($row) {
                $asset_data = json_decode($row->json_asset_data, true);
                return $asset_data;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->peminjaman_asset->status == 'disetujui') {
                    $element .= '<form action="' . route('admin.peminjaman.detail-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                    $element .= csrf_field();
                    $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                    <i class="fa fa-trash"></i>
                                </button>';
                    $element .= '</form>';
                }
                return $element;
            })
            ->rawColumns(['action', 'asset_data'])
            ->make(true);
    }

    public function logPeminjamanDatatable(Request $request)
    {
        $query = LogPeminjamanAsset::query()
            ->select([
                'log_peminjaman_assets.*',
                'peminjaman_assets.code',
                'peminjaman_assets.id as id_peminjaman_asset',
            ])
            ->join('peminjaman_assets', 'peminjaman_assets.id', '=', 'log_peminjaman_assets.peminjaman_asset_id');

        $filter = $request->toArray();

        if (isset($request->searchKeyword)) {
            $query->whereHas('peminjaman_asset', function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->searchKeyword . '%');
            });
        }

        if (isset($request->start_date)) {
            $query->where('log_peminjaman_assets.created_at', '>=', $request->start_date . ' 00:00:00');
        }

        if (isset($request->end_date)) {
            $query->where('log_peminjaman_assets.created_at', '<=', $request->end_date . ' 23:59:00');
        }

        if (isset($request->peminjaman_asset_id)) {
            $query->where('peminjaman_asset_id', $request->peminjaman_asset_id);
        }

        if (isset($request->is_it)) {
            $query->whereHas('peminjaman_asset', function ($query) use ($request) {
                $query->where('is_it', $request->is_it);
            });
        }

        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        if (0 == $order_column_index) {
            $query->orderBy('created_at', 'DESC');
        }

        if (1 == $order_column_index) {
            $query->orderBy('created_at', $order_column_dir);
        }

        if (2 == $order_column_index) {
            $query->orderBy('peminjaman_assets.code', $order_column_dir);
        }

        return DataTables::of($query)
            ->addColumn('created_by_name', function ($item) {
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $item->created_by == null ? null : $this->userQueryServices->findById($item->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }

                return $name;
            })
            ->addColumn('kode_asset', function ($item) {
                $find_detail_asset = DetailPeminjamanAsset::with(['asset'])->where('id_peminjaman_asset', $item->id_peminjaman_asset)->get();
                $element = '';
                foreach ($find_detail_asset as $index=>$item) {
                    if ($index >= 1) {
                        $element .= ', ';
                    }
                    $element .= $item->asset->kode_asset . ' (' . $item->asset->deskripsi . ')';
                }

                return $element;
            })
            ->addIndexColumn()
            ->make(true);
    }
}
