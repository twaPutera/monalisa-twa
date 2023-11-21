<?php

namespace App\Services\PemutihanAsset;

use App\Models\AssetData;
use App\Helpers\SsoHelpers;
use Illuminate\Http\Request;
use App\Models\PemutihanAsset;
use Yajra\DataTables\DataTables;
use App\Models\DetailPemutihanAsset;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class PemutihanAssetDatatableServices
{
    protected $userSsoQueryServices;
    protected $userQueryServices;
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }
    public $assetInPemutihan = [];
    public function datatable(Request $request)
    {
        $query = PemutihanAsset::query();
        // $query->join('detail_pemutihan_assets', 'pemutihan_assets.id', 'detail_pemutihan_assets.id_pemutihan_asset');
        // $query->join('asset_data', 'asset_data.id', 'detail_pemutihan_assets.id_asset_data');
        // $query->select([
        //     'pemutihan_assets.*',
        // ]);

        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    // $query->where('asset_data.is_it', '1');
                    $query->where('is_it', 1);
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    // $query->where('asset_data.is_it', '0');
                    $query->where('is_it', 0);
                }
            }
        }
        // SEARCH
        $search = $request->toArray();
        $search_column = $search['search']['value'];
        if ($search_column != null) {
            $query->where(function ($query) use ($search_column) {
                $query->where('nama_pemutihan', 'like', '%' . $search_column . '%')
                    ->orWhere('tanggal', 'like', '%' . $search_column . '%')
                    ->orWhere('keterangan', 'like', '%' . $search_column . '%')
                    ->orWhere('no_memo', 'like', '%' . $search_column . '%');
            });
        }

        $query->orderBy('created_at', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('keterangan', function ($item) {
                return ! isset($item->keterangan) ? 'Tidak Ada' : $item->keterangan;
            })
            ->addColumn('tanggal', function ($item) {
                return ! isset($item->tanggal) ? 'Tidak Ada' : $item->tanggal;
            })
            ->addColumn('nama_pemutihan', function ($item) {
                return ! isset($item->nama_pemutihan) ? 'Tidak Ada' : $item->nama_pemutihan;
            })
            ->addColumn('no_memo', function ($item) {
                return ! isset($item->no_memo) ? 'Tidak Ada' : $item->no_memo;
            })
            ->addColumn('status', function ($item) {
                return ! isset($item->status) ? 'Tidak Ada' : $item->status;
            })
            ->addColumn('is_it', function ($item) {
                return ! isset($item->is_it) ? 'Tidak Ada' : $item->is_it;
            })
            ->addColumn('created_by', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                    $name = isset($user[0]) ? collect($user[0]) : null;
                } else {
                    $user = $this->userQueryServices->findById($item->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status == 'Draft') {
                    $element .= '<form action="' . route('admin.pemutihan-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                    $element .= csrf_field();
                    $element .= '<a href="' . route('admin.pemutihan-asset.edit', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                    <i class="fa fa-trash"></i>
                                </button>';
                    $element .= '</form>';
                } elseif ($item->status == 'Ditolak') {
                    $element .= '<form action="' . route('admin.pemutihan-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                    $element .= csrf_field();
                    $element .= '<a href="' . route('admin.pemutihan-asset.edit.ditolak', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                    <i class="fa fa-trash"></i>
                                </button>';
                    $element .= '</form>';
                } else {
                    $element .= '<button type="button" onclick="detail(this)" data-url_detail="' . route('admin.pemutihan-asset.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                    <i class="fa fa-eye"></i>
                    </button>';
                }
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function datatableAsset(Request $request)
    {
        $query = AssetData::query();
        $query->orderBy('created_at', 'desc');

        if (isset($request->id_pemutihan)) {
            $pemutihan = PemutihanAsset::with(['detail_pemutihan_asset'])->findOrFail($request->id_pemutihan);
            foreach ($pemutihan->detail_pemutihan_asset as $item) {
                array_push($this->assetInPemutihan, $item->id_asset_data);
            }
        } else {
            $this->assetInPemutihan = [];
        }
        if (isset($request->status_kondisi)) {
            if ($request->status_kondisi != 'semua') {
                $query->where('status_kondisi', $request->status_kondisi);
            }
        }

        if (isset($request->jenis)) {
            $query->where('id_kategori_asset', $request->jenis);
        }
        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $query->where('is_it', '1');
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $query->where('is_it', '0');
                }
            }
        }
        $query->where('is_pemutihan', 0);
        $query->where('is_draft', '0');
        return DataTables::of($query)
            ->addColumn('id', function ($item) {
                if (in_array($item->id, $this->assetInPemutihan)) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $data = '';
                $data .= '<div class="form-check text-center">
                            <input type="checkbox" class="form-check-input check-item" value="' . $item->id . '"  name="id_checkbox[]" multiple id="exampleCheck1" ' . $checked . '>
                            <label class="form-check-label" for="exampleCheck1"></label>
                         </div>';
                return $data;
            })
            ->addColumn('kode_asset', function ($item) {
                return empty($item->kode_asset) ? 'Tidak Ada' : $item->kode_asset;
            })
            ->addColumn('deskripsi', function ($item) {
                return empty($item->deskripsi) ? 'Tidak Ada' : $item->deskripsi;
            })
            ->addColumn('jenis_asset', function ($item) {
                return empty($item->kategori_asset->nama_kategori) ? 'Tidak Ada' : $item->kategori_asset->nama_kategori;
            })
            ->addColumn('lokasi_asset', function ($item) {
                return empty($item->lokasi->nama_lokasi) ? 'Tidak Ada' : $item->lokasi->nama_lokasi;
            })
            ->addColumn('kondisi_asset', function ($item) {
                return empty($item->status_kondisi) ? 'Tidak Ada' : $item->status_kondisi;
            })
            ->rawColumns(['id'])
            ->make(true);
    }

    public function datatableDetail(Request $request)
    {
        $query = DetailPemutihanAsset::query();
        // $query->with(['asset_data']);
        $query->join('asset_data', 'detail_pemutihan_assets.id_asset_data', 'asset_data.id');
        $query->select([
            'detail_pemutihan_assets.*',
            'asset_data.is_pemutihan',
            'asset_data.is_it',
            'asset_data.deskripsi',
            'asset_data.kode_asset',
            'asset_data.status_kondisi',
        ]);
        if (isset($request->id_pemutihan_detail)) {
            $query->where('id_pemutihan_asset', $request->id_pemutihan_detail);
        }

        if (isset($request->is_pemutihan)) {
            $query->where('asset_data.is_pemutihan', $request->is_pemutihan);
        }
        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $query->where('asset_data.is_it', '1');
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $query->where('asset_data.is_it', '0');
                }
            }
        }

        // Search
        $search = $request->toArray();
        $search_column = $search['search']['value'];
        if ($search_column != null) {
            $query->where(function ($query) use ($search_column) {
                $query->where('keterangan_pemutihan', 'like', '%' . $search_column . '%')
                    ->orWhere('asset_data.deskripsi', 'like', '%' . $search_column . '%')
                    ->orWhere('asset_data.status_kondisi', 'like', '%' . $search_column . '%')
                    ->orWhere('asset_data.kode_asset', 'like', '%' . $search_column . '%');
            });
        }

        $query->orderBy('detail_pemutihan_assets.created_at', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file_gambar', function ($item) {
                $data = '';
                $data .= '<button type="button" onclick="showPemutihanAsset(this)"';
                $data .= 'data-url_detail="' . route('admin.pemutihan-asset.edit.listing-asset.get-image', $item->id) . '"';
                $data .= 'class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $data;
            })
            ->addColumn('button_show_asset', function ($item) {
                $element = '';
                $element .= '<a href="' . route('admin.listing-asset.detail', $item->id_asset_data) . '" class="btn btn-sm btn-icon">
                                <i class="fa fa-eye"></i>
                            </a>';
                return $element;
            })
            ->addColumn('kode_asset', function ($item) {
                $json_asset = json_decode($item->json_asset);
                return empty($json_asset->kode_asset) ? 'Tidak Ada' : $json_asset->kode_asset;
            })
            ->addColumn('deskripsi', function ($item) {
                $json_asset = json_decode($item->json_asset);
                return empty($json_asset->deskripsi) ? 'Tidak Ada' : $json_asset->deskripsi;
            })
            ->addColumn('jenis_asset', function ($item) {
                $json_asset = json_decode($item->json_asset);
                return empty($json_asset->kategori_asset->nama_kategori) ? 'Tidak Ada' : $json_asset->kategori_asset->nama_kategori;
            })
            ->addColumn('is_inventaris', function ($item) {
                $json_asset = json_decode($item->json_asset);
                return  isset($json_asset->is_inventaris) && $json_asset->is_inventaris == 1 ? 'Inventaris' : 'Asset';
            })
            ->addColumn('lokasi_asset', function ($item) {
                $json_asset = json_decode($item->json_asset);
                return empty($json_asset->lokasi->nama_lokasi) ? 'Tidak Ada' : $json_asset->lokasi->nama_lokasi;
            })
            ->addColumn('kondisi_asset', function ($item) {
                $json_asset = json_decode($item->json_asset);
                return empty($json_asset->status_kondisi) ? 'Tidak Ada' : ucWords($json_asset->status_kondisi);
            })
            ->addColumn('keterangan_pemutihan', function ($item) {
                return empty($item->keterangan_pemutihan) ? 'Tidak Ada' : $item->keterangan_pemutihan;
            })
            ->rawColumns(['file_gambar', 'button_show_asset'])
            ->make(true);
    }
}
