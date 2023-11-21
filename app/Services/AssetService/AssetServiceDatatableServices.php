<?php

namespace App\Services\AssetService;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\AssetData;
use App\Helpers\SsoHelpers;
use Illuminate\Http\Request;
use App\Models\LogServiceAsset;
use Yajra\DataTables\DataTables;
use App\Models\PerencanaanServices;
use App\Services\User\UserQueryServices;
use Yajra\DataTables\Contracts\DataTable;
use App\Services\UserSso\UserSsoQueryServices;

class AssetServiceDatatableServices
{
    protected $ssoServices;
    protected $userQueryServices;
    protected $userSsoQueryServices;
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function datatableLog(Request $request)
    {
        $query = LogServiceAsset::query();
        $query->where('id_service', $request->id_service);
        $query->orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addColumn('tanggal', function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d') ?? 'Tidak Ada';
            })
            ->addColumn('message_log', function ($item) {
                return $item->message_log ?? 'Tidak Ada';
            })
            ->addColumn('created_by', function ($item) {
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
            ->make(true);
    }

    public function datatable(Request $request)
    {
        $query = Service::query()
            ->with(['kategori_service', 'image', 'detail_service']);

        if (isset($request->id_asset_data)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->where('id_asset_data', $request->id_asset_data);
            });
        }

        if (isset($request->id_kategori_service)) {
            $query->where('id_kategori_service', $request->id_kategori_service);
        }

        if (isset($request->id_kategori_asset)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->whereHas('asset_data', function ($query) use ($request) {
                    $query->where('id_kategori_asset', $request->id_kategori_asset);
                });
            });
        }

        if (isset($request->status_service)) {
            $query->where('status_service', $request->status_service);
        }

        if (isset($request->id_lokasi)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->where('id_lokasi', $request->id_lokasi);
            });
        }

        if (isset($request->awal)) {
            $query->where('tanggal_mulai', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $query->where('tanggal_selesai', '<=', $request->akhir);
        }

        if (isset($request->year)) {
            $query->whereYear('tanggal_mulai', $request->year);
        }

        if (isset($request->month)) {
            $query->whereMonth('tanggal_mulai', $request->month);
        }

        if (isset($request->keyword)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->whereHas('asset_data', function ($query) use ($request) {
                    $query->where('deskripsi', 'like', '%' . $request->keyword . '%');
                });
            });
        }

        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $query->whereHas('detail_service', function ($query) use ($request) {
                        $query->whereHas('asset_data', function ($query) use ($request) {
                            $query->where('is_it', '1');
                        });
                    });
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $query->whereHas('detail_service', function ($query) use ($request) {
                        $query->whereHas('asset_data', function ($query) use ($request) {
                            $query->where('is_it', '0');
                        });
                    });
                }
            }
        }
        $query->orderBy('services.created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_service', function ($item) {
                return $item->kategori_service->nama_service ?? 'Tidak Ada';
            })
            ->addColumn('user', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->guid_pembuat == null ? null : $this->userSsoQueryServices->getUserByGuid($item->guid_pembuat);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $this->userQueryServices->findById($item->guid_pembuat);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('kode_services', function ($item) {
                return $item->kode_services ?? 'Tidak Ada';
            })
            ->addColumn('deskripsi_service', function ($item) {
                return $item->detail_service->catatan ?? 'Tidak Ada';
            })
            ->addColumn('asset_deskripsi', function ($item) {
                return $item->detail_service->asset_data->deskripsi ?? 'Tidak Ada';
            })
            ->addColumn('is_inventaris', function ($item) {
                return $item->detail_service->asset_data->is_inventaris;
            })
            ->addColumn('nama_group', function ($item) {
                return $item->detail_service->asset_data->kelas_asset->nama_kelas ?? 'Tidak Ada';
            })
            ->addColumn('nama_kategori', function ($item) {
                return $item->detail_service->asset_data->kategori_asset->nama_kategori ?? 'Tidak Ada';
            })
            ->addColumn('btn_show_service', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAssetServices(this)" data-url_detail="' . route('admin.listing-asset.service-asset.show', $item->id) . '" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $element;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status_service != 'selesai') {
                    $element .= '<button type="button" onclick="editService(this)" data-id_asset="' . $item->detail_service->id_asset_data . '" data-url_edit="' . route('admin.services.edit', $item->id) . '" data-url_update="' . route('admin.services.update', $item->id) . '" class="btn btn-sm btn-warning mr-1 me-1 btn-icon"><i class="fa fa-edit"></i></button>';
                    $element .= '<button type="button" onclick="editStatusService(this)" data-id_asset="' . $item->detail_service->id_asset_data . '" data-url_edit_status="' . route('admin.services.edit.status', $item->id) . '" data-url_update_status="' . route('admin.services.update.status', $item->id) . '" class="btn btn-sm btn-success mr-1 me-1 btn-icon"><i class="fa fa-info-circle"></i></button>';
                    $element .= '<button type="button" data-id_detail_service="' . $item->id . '" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                } else {
                    $element .= '<button type="button" data-id_detail_service="' . $item->id . '" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                }
                return $element;
            })
            ->addColumn('dashboard', function ($item) {
                $element = '';
                $user = SsoHelpers::getUserLogin();
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    if ($item->detail_service->asset_data->is_it == 1) {
                        if ($item->status_service != 'selesai') {
                            $element .= '<button type="button" onclick="editStatusService(this)" data-id_asset="' . $item->detail_service->id_asset_data . '" data-url_edit_status="' . route('admin.services.edit.status', $item->id) . '" data-url_update_status="' . route('admin.services.update.status', $item->id) . '" class="btn btn-sm btn-success mr-1 me-1 btn-icon"><i class="fa fa-info-circle"></i></button>';
                        } else {
                            $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                        }
                    } else {
                        $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                    }
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    if ($item->detail_service->asset_data->is_it == 0) {
                        if ($item->status_service != 'selesai') {
                            $element .= '<button type="button" onclick="editStatusService(this)" data-id_asset="' . $item->detail_service->id_asset_data . '" data-url_edit_status="' . route('admin.services.edit.status', $item->id) . '" data-url_update_status="' . route('admin.services.update.status', $item->id) . '" class="btn btn-sm btn-success mr-1 me-1 btn-icon"><i class="fa fa-info-circle"></i></button>';
                        } else {
                            $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                        }
                    } else {
                        $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                    }
                } else {
                    if ($item->status_service != 'selesai') {
                        $element .= '<button type="button" onclick="editStatusService(this)" data-id_asset="' . $item->detail_service->id_asset_data . '" data-url_edit_status="' . route('admin.services.edit.status', $item->id) . '" data-url_update_status="' . route('admin.services.update.status', $item->id) . '" class="btn btn-sm btn-success mr-1 me-1 btn-icon"><i class="fa fa-info-circle"></i></button>';
                    } else {
                        $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                    }
                }
                return $element;
            })
            ->addColumn('asset_data', function ($item) {
                $asset = AssetData::query()
                    ->join('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset')
                    ->join('group_kategori_assets', 'group_kategori_assets.id', '=', 'kategori_assets.id_group_kategori_asset')
                    ->select([
                        'asset_data.id',
                        'asset_data.deskripsi',
                        'asset_data.is_inventaris',
                        'kategori_assets.nama_kategori',
                        'group_kategori_assets.nama_group',
                    ])->where('asset_data.id', $item->detail_service->id_asset_data)
                    ->first();
                return isset($asset) ? $asset->toArray() : [];
            })
            ->addColumn('checkbox', function ($item) {
                $element = '';
                $element .= '<input type="checkbox" name="id[]" value="' . $item->id . '">';
                return $element;
            })
            ->rawColumns(['btn_show_service', 'asset_data', 'action', 'checkbox', 'dashboard'])
            ->make(true);
    }

    public function datatableHistoryServices(Request $request)
    {
        $query = LogServiceAsset::query();
        $filter = $request->toArray();
        $query->leftJoin('services', 'log_service_assets.id_service', '=', 'services.id');
        $query->leftJoin('kategori_services', 'services.id_kategori_service', '=', 'kategori_services.id');
        $query->leftJoin('detail_services', 'detail_services.id_service', '=', 'services.id');
        $query->leftJoin('asset_data', 'detail_services.id_asset_data', '=', 'asset_data.id');
        $query->leftJoin('kategori_assets', 'asset_data.id_kategori_asset', '=', 'kategori_assets.id');
        $query->leftJoin('group_kategori_assets', 'kategori_assets.id_group_kategori_asset', '=', 'group_kategori_assets.id');
        $query->leftJoin('lokasis', 'detail_services.id_lokasi', '=', 'lokasis.id');
        $query->select([
            'services.kode_services',
            'services.tanggal_mulai',
            'services.tanggal_selesai',
            'asset_data.kode_asset',
            'asset_data.is_inventaris',
            'kategori_services.nama_service',
            'asset_data.deskripsi',
            'kategori_assets.nama_kategori',
            'lokasis.nama_lokasi',
            'asset_data.status_kondisi',
            'group_kategori_assets.nama_group',
            'detail_services.permasalahan',
            'detail_services.tindakan',
            'detail_services.catatan',
            'log_service_assets.status',
            'log_service_assets.created_at as log_terakhir',
            'log_service_assets.message_log',
            'log_service_assets.created_by',
        ]);

        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $query->where('asset_data.is_it', 1);
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $query->where('asset_data.is_it', 0);
                }
            }
        }

        if (isset($request->id_kategori_asset)) {
            $query->where('asset_data.id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->status_service)) {
            if ($request->status_service != 'all') {
                $query->where('log_service_assets.status', $request->status_service);
            }
        }

        if (isset($request->id_lokasi)) {
            if ($request->id_lokasi != 'root') {
                $query->where('detail_services.id_lokasi', $request->id_lokasi);
            }
        }

        if (isset($request->id_asset_data)) {
            $query->where('detail_services.id_asset_data', $request->id_asset_data);
        }

        if (isset($request->awal)) {
            $query->where('services.tanggal_mulai', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $query->where('services.tanggal_selesai', '<=', $request->akhir);
        }

        if (isset($request->keyword)) {
            $query->where('asset_data.deskripsi', 'like', '%' . $request->keyword . '%');
        }

        // SORT
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';
        if (1 == $order_column_index) {
            $query->orderBy('services.tanggal_mulai', $order_column_dir);
        }
        if (2 == $order_column_index) {
            $query->orderBy('services.tanggal_selesai', $order_column_dir);
        }
        if (3 == $order_column_index) {
            $query->orderBy('log_service_assets.created_at', $order_column_dir);
        }
        if (4 == $order_column_index) {
            $query->orderBy('asset_data.kode_asset', $order_column_dir);
        }
        if (5 == $order_column_index) {
            $query->orderBy('asset_data.deskripsi', $order_column_dir);
        }
        if (6 == $order_column_index) {
            $query->orderBy('asset_data.is_inventaris', $order_column_dir);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_service', function ($item) {
                return $item->nama_service ?? 'Tidak Ada';
            })
            ->addColumn('dilakukan_oleh', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $this->userQueryServices->findById($item->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('kode_services', function ($item) {
                return $item->kode_services ?? 'Tidak Ada';
            })
            ->addColumn('kode_asset', function ($item) {
                return $item->kode_asset ?? 'Tidak Ada';
            })
            ->addColumn('lokasi', function ($item) {
                return $item->nama_lokasi ?? 'Tidak Ada';
            })
            ->addColumn('asset_deskripsi', function ($item) {
                return $item->deskripsi ?? 'Tidak Ada';
            })
            ->addColumn('is_inventaris', function ($item) {
                return $item->is_inventaris ?? 'Tidak Ada';
            })
            ->addColumn('tanggal_mulai', function ($item) {
                return $item->tanggal_mulai ?? 'Tidak Ada';
            })
            ->addColumn('tanggal_selesai', function ($item) {
                return $item->tanggal_selesai ?? 'Tidak Ada';
            })
            ->addColumn('permasalahan', function ($item) {
                return $item->permasalahan ?? 'Tidak Ada';
            })
            ->addColumn('tindakan', function ($item) {
                return $item->tindakan ?? 'Tidak Ada';
            })
            ->addColumn('catatan', function ($item) {
                return $item->catatan ?? 'Tidak Ada';
            })

            ->addColumn('status_service', function ($item) {
                return $item->status ?? 'Tidak Ada';
            })
            ->addColumn('log_terakhir', function ($item) {
                return $item->log_terakhir ?? 'Tidak Ada';
            })
            ->addColumn('aktifitas', function ($item) {
                return $item->message_log ?? 'Tidak Ada';
            })
            ->addColumn('nama_group', function ($item) {
                return $item->nama_group ?? 'Tidak Ada';
            })
            ->addColumn('nama_kategori', function ($item) {
                return $item->nama_kategori ?? 'Tidak Ada';
            })

            ->make(true);
    }

    public function datatablePerencanaanServices(Request $request)
    {
        $query = PerencanaanServices::query()
            ->select([
                'perencanaan_services.*',
                'asset_data.kode_asset',
                'asset_data.deskripsi as asset_deskripsi',
            ])
            ->join('asset_data', 'asset_data.id', '=', 'perencanaan_services.id_asset_data');

        if (isset($request->id_asset_data)) {
            $query->where('id_asset_data', $request->id_asset_data);
        }

        if (isset($request->id_log_opname)) {
            $query->where('id_log_opname', $request->id_log_opname);
        }

        if (isset($request->status)) {
            $query->where('status', $request->status);
        }

        if (isset($request->tanggal_perencanaan)) {
            $query->where('tanggal_perencanaan', $request->tanggal_perencanaan);
        }

        if (isset($request->awal)) {
            $query->where('tanggal_perencanaan', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $query->where('tanggal_perencanaan', '<=', $request->akhir);
        }

        if (isset($request->limit)) {
            $query->limit($request->limit);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('dashboard', function ($item) {
                $element = '';
                $user = SsoHelpers::getUserLogin();
                if ($user) {
                    if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                        if ($item->asset_data->is_it == 1) {
                            $element .= '<button type="button" onclick="addServicesFromPerencanaan(this)" data-url_show="' . route('admin.services.find-perencanaan-service', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                <i class="fa fa-eye"></i>
                            </button>';
                        }
                    } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                        if ($item->asset_data->is_it == 0) {
                            $element .= '<button type="button" onclick="addServicesFromPerencanaan(this)" data-url_show="' . route('admin.services.find-perencanaan-service', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                <i class="fa fa-eye"></i>
                            </button>';
                        }
                    } else {
                        $element .= '<button type="button" onclick="addServicesFromPerencanaan(this)" data-url_show="' . route('admin.services.find-perencanaan-service', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                            <i class="fa fa-eye"></i>
                        </button>';
                    }
                }
                return $element;
            })
            ->rawColumns(['dashboard'])
            ->make(true);
    }
}
