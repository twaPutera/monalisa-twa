<?php

namespace App\Services\InventarisData;

use Illuminate\Http\Request;
use App\Models\InventoriData;
use App\Models\RequestInventori;
use Yajra\DataTables\DataTables;
use App\Models\KategoriInventori;
use App\Models\LogRequestInventori;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailRequestInventori;
use App\Models\LogPenambahanInventori;
use App\Models\LogPenguranganInventori;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class InventarisDataDatatableServices
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
        $query = InventoriData::query();

        $query->with(['kategori_inventori', 'satuan_inventori']);

        $filter = $request->toArray();

        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        if (2 == $order_column_index) {
            $query->orderBy('kode_inventori', $order_column_dir);
        }

        if (3 == $order_column_index) {
            $query->orderBy('nama_inventori', $order_column_dir);
        }

        if (4 == $order_column_index) {
            $subquery = KategoriInventori::select('kategori_inventories.id')
                ->whereColumn('kategori_inventories.id', 'inventori_data.id_kategori_inventori')
                ->orderBy('kategori_inventories.nama_kategori', $order_column_dir)
                ->limit(1);

            $query->orderByRaw('(' . $subquery->toSql() . ') ' . $order_column_dir, $subquery->getBindings());
        }

        if (5 == $order_column_index) {
            $query->orderBy('jumlah_sebelumnya', $order_column_dir);
        }

        if (6 == $order_column_index) {
            $query->orderBy('jumlah_saat_ini', $order_column_dir);
        }

        if (7 == $order_column_index) {
            $query->orderBy('deskripsi_inventori', $order_column_dir);
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori', function ($item) {
                return ! empty($item->kategori_inventori->nama_kategori) ? $item->kategori_inventori->nama_kategori : 'Tidak Ada';
            })
            ->addColumn('sebelumnya', function ($item) {
                return ! empty($item->jumlah_sebelumnya) || ! empty($item->satuan_inventori->nama_satuan) ? $item->jumlah_sebelumnya . ' ' . $item->satuan_inventori->nama_satuan : 'Tidak Ada';
            })
            ->addColumn('saat_ini', function ($item) {
                return ! empty($item->jumlah_saat_ini) || ! empty($item->satuan_inventori->nama_satuan) ? $item->jumlah_saat_ini . ' ' . $item->satuan_inventori->nama_satuan : 'Tidak Ada';
            })
            ->addColumn('action', function ($item) {
                $element = '';

                if (Auth::user()->role == 'admin') {
                    $element .= '<form action="' . route('admin.listing-inventaris.delete', $item->id) . '" class="form-confirm" method="POST">';
                    $element .= csrf_field();
                    $element .= '<a href="' . route('admin.listing-inventaris.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>';
                    $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.listing-inventaris.edit', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>';
                    $element .= '<button type="button" onclick="stokEdit(this)" data-url_edit="' . route('admin.listing-inventaris.edit.stok', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update.stok', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-info">
                                    <i class="fa fa-box"></i>
                                </button>';
                    $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                    <i class="fa fa-trash"></i>
                                </button>';
                    $element .= '</form>';
                } else {
                    $element .= '<a href="' . route('admin.listing-inventaris.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>';
                    $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.listing-inventaris.edit', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>';
                    $element .= '<button type="button" onclick="stokEdit(this)" data-url_edit="' . route('admin.listing-inventaris.edit.stok', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update.stok', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-info">
                                    <i class="fa fa-box"></i>
                                </button>';
                }
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function datatableHistory(Request $request)
    {
        $query = LogRequestInventori::query();
        $query->leftJoin('request_inventories', 'request_inventories.id', 'log_request_inventories.request_inventori_id');
        // $query->leftJoin('detail_request_inventories', 'request_inventories.id', 'detail_request_inventories.request_inventori_id');
        $query->select([
            'log_request_inventories.*',
            'request_inventories.kode_request',
            'request_inventories.tanggal_pengambilan',
            'request_inventories.created_at as tanggal_permintaan',
            'request_inventories.alasan',
            'request_inventories.no_memo',
            'request_inventories.guid_pengaju',
            'request_inventories.unit_kerja',
            'request_inventories.jabatan',
        ]);

        if (isset($request->keyword)) {
            $query->where('kode_request', 'like', '%' . $request->keyword . '%')
                ->orWhere('no_memo', 'like', '%' . $request->keyword . '%')
                ->orWhere('created_by', 'like', '%' . $request->keyword . '%')
                ->orWhere('unit_kerja', 'like', '%' . $request->keyword . '%')
                ->orWhere('jabatan', 'like', '%' . $request->keyword . '%')
                ->orWhere('message', 'like', '%' . $request->keyword . '%')
                ->orWhere('alasan', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->awal_permintaan)) {
            $query->where('request_inventories.created_at', '>=', $request->awal_permintaan . ' 00:00:00');
        }

        if (isset($request->akhir_permintaan)) {
            $query->where('request_inventories.created_at', '<=', $request->akhir_permintaan . ' 23:59:00');
        }

        if (isset($request->awal_pengambilan)) {
            $query->where('tanggal_pengambilan', '>=', $request->awal_pengambilan);
        }

        if (isset($request->akhir_pengambilan)) {
            $query->where('tanggal_pengambilan', '<=', $request->akhir_pengambilan);
        }

        if (isset($request->status_permintaan)) {
            if ($request->status_permintaan != 'all') {
                $query->where('log_request_inventories.status', $request->status_permintaan);
            }
        }

        // Order
        $filter = $request->toArray();
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        if (0 == $order_column_index) {
            $query->orderBy('request_inventories.created_at', 'DESC');
        }
        if (1 == $order_column_index) {
            $query->orderBy('request_inventories.created_at', $order_column_dir);
        }

        if (2 == $order_column_index) {
            $query->orderBy('kode_request', $order_column_dir);
        }

        if (4 == $order_column_index) {
            $query->orderBy('created_at', $order_column_dir);
        }

        if (5 == $order_column_index) {
            $query->orderBy('tanggal_pengambilan', $order_column_dir);
        }

        if (6 == $order_column_index) {
            $query->orderBy('guid_pengaju', $order_column_dir);
        }

        if (7 == $order_column_index) {
            $query->orderBy('no_memo', $order_column_dir);
        }

        if (8 == $order_column_index) {
            $query->orderBy('unit_kerja', $order_column_dir);
        }

        if (9 == $order_column_index) {
            $query->orderBy('jabatan', $order_column_dir);
        }

        if (10 == $order_column_index) {
            $query->orderBy('alasan', $order_column_dir);
        }

        if (11 == $order_column_index) {
            $query->orderBy('message', $order_column_dir);
        }

        // $query->orderBy('log_request_inventories.created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode_permintaan', function ($item) {
                return ! empty($item->kode_request) ? $item->kode_request : 'Tidak Ada';
            })
            ->addColumn('message', function ($item) {
                return ! empty($item->message) ? $item->message : 'Tidak Ada';
            })
            ->addColumn('status', function ($item) {
                return ! empty($item->status) ? $item->status : 'Tidak Ada';
            })
            ->addColumn('no_memo', function ($item) {
                return ! empty($item->no_memo) ? $item->no_memo : 'Tidak Ada';
            })
            ->addColumn('jabatan', function ($item) {
                return ! empty($item->jabatan) ? $item->jabatan : 'Tidak Ada';
            })
            ->addColumn('unit_kerja', function ($item) {
                return ! empty($item->unit_kerja) ? $item->unit_kerja : 'Tidak Ada';
            })
            ->addColumn('user_pengaju', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->guid_pengaju == null ? null : $this->userSsoQueryServices->getUserByGuid($item->guid_pengaju);
                    $name = isset($user[0]) ? collect($user[0]) : null;
                } else {
                    $user = $this->userQueryServices->findById($item->guid_pengaju);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('alasan', function ($item) {
                return ! empty($item->alasan) ? $item->alasan : 'Tidak Ada';
            })
            ->addColumn('created_by', function ($item) {
                return ! empty($item->created_by) ? $item->created_by : 'Tidak Ada';
            })
            ->addColumn('tanggal_permintaan', function ($item) {
                return ! empty($item->tanggal_permintaan) ? $item->tanggal_permintaan : 'Tidak Ada';
            })
            ->addColumn('tanggal_pengambilan', function ($item) {
                return ! empty($item->tanggal_pengambilan) ? $item->tanggal_pengambilan : 'Tidak Ada';
            })
            ->addColumn('log_terakhir', function ($item) {
                return ! empty($item->created_at) ? $item->created_at : 'Tidak Ada';
            })
            ->addColumn('kode_bahan_habis_pakai', function ($item) {
                $find_detail_asset = DetailRequestInventori::with(['inventori'])->where('request_inventori_id', $item->request_inventori_id)->get();
                $element = '';
                foreach ($find_detail_asset as $index => $item) {
                    if ($index >= 1) {
                        $element .= ', ';
                    }
                    $element .= $item->inventori->kode_inventori . ' (' . $item->inventori->deskripsi_inventori . ')';
                }

                return $element;
            })
            ->make(true);
    }

    public function datatablePermintaan(Request $request)
    {
        $query = RequestInventori::query();
        $query->with(['detail_request_inventori']);
        // $query->orderBy('created_at', 'ASC');

        // Search
        $request_arr = $request->toArray();
        $search_column = $request_arr['search']['value'];
        if ($search_column != null) {
            // $query->where(function ($query) use ($search_column) {
            $query->where('created_at', 'like', '%' . $search_column . '%')
                // ->orWhereHas('asset_data', function ($query) use ($search_column) {
                //     $query->where('asset_data.deskripsi', 'like', '%' . $search_column . '%');
                // })
                // ->orWhereHas('lokasi', function ($query) use ($search_column) {
                //     $query->where('lokasis.nama_lokasi', 'like', '%' . $search_column . '%');
                // })
                ->orWhere('kode_request', 'like', '%' . $search_column . '%')
                ->orWhere('tanggal_pengambilan', 'like', '%' . $search_column . '%')
                ->orWhere('alasan', 'like', '%' . $search_column . '%')
                ->orWhere('unit_kerja', 'like', '%' . $search_column . '%')
                ->orWhere('jabatan', 'like', '%' . $search_column . '%')
                ->orWhere('no_memo', 'like', '%' . $search_column . '%');
            // });
        }

        // Filter
        $order_column_index = $request_arr['order'][0]['column'] ?? 0;
        $order_column_dir = $request_arr['order'][0]['dir'] ?? 'desc';

        if (2 == $order_column_index) {
            $query->orderBy('created_at', $order_column_dir);
        }

        if (3 == $order_column_index) {
            $query->orderBy('kode_request', $order_column_dir);
        }

        if (4 == $order_column_index) {
            $query->orderBy('tanggal_pengambilan', $order_column_dir);
        }

        if (5 == $order_column_index) {
            $query->orderBy('no_memo', $order_column_dir);
        }

        if (6 == $order_column_index) {
            $query->orderBy('guid_pengaju', $order_column_dir);
        }

        if (7 == $order_column_index) {
            $query->orderBy('unit_kerja', $order_column_dir);
        }

        if (8 == $order_column_index) {
            $query->orderBy('jabatan', $order_column_dir);
        }

        if (9 == $order_column_index) {
            $query->orderBy('status', $order_column_dir);
        }

        if (10 == $order_column_index) {
            $query->orderBy('alasan', $order_column_dir);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_permintaan', function ($item) {
                return $item->created_at ?? 'Tidak Ada';
            })
            ->addColumn('kode_permintaan', function ($item) {
                return $item->kode_request ?? 'Tidak Ada';
            })
            ->addColumn('tanggal_pengambilan', function ($item) {
                return $item->tanggal_pengambilan ?? 'Tidak Ada';
            })
            ->addColumn('no_memo', function ($item) {
                return $item->no_memo ?? 'Tidak Ada';
            })
            ->addColumn('alasan', function ($item) {
                return $item->alasan ?? 'Tidak Ada';
            })
            ->addColumn('status', function ($item) {
                return $item->status ?? 'Tidak Ada';
            })
            ->addColumn('unit_kerja', function ($item) {
                return $item->unit_kerja ?? 'Tidak Ada';
            })
            ->addColumn('jabatan', function ($item) {
                return $item->jabatan ?? 'Tidak Ada';
            })
            ->addColumn('user_pengaju', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->guid_pengaju == null ? null : $this->userSsoQueryServices->getUserByGuid($item->guid_pengaju);
                    $name = isset($user[0]) ? collect($user[0]) : null;
                } else {
                    $user = $this->userQueryServices->findById($item->guid_pengaju);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status != 'ditolak') {
                    $element .= '<a href="' . route('admin.permintaan-inventaris.realisasi', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>';
                }
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function datatableLogPermintaan(Request $request)
    {
        $query = LogRequestInventori::query();
        if (isset($request->request_inventori_id)) {
            $query->where('request_inventori_id', $request->request_inventori_id);
        }
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function datatablePenambahan(Request $request)
    {
        $query = LogPenambahanInventori::query();
        $query->with(['inventori_data']);
        $query->where('id_inventori', $request->id_inventaris);
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($item) {
                return $item->jumlah . ' ' . $item->inventori_data->satuan_inventori->nama_satuan;
            })
            ->make(true);
    }

    public function datatablePengurangan(Request $request)
    {
        $query = LogPenguranganInventori::query();
        $query->with(['inventori_data']);
        $query->where('id_inventori', $request->id_inventaris);
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($item) {
                return $item->jumlah . ' ' . $item->inventori_data->satuan_inventori->nama_satuan;
            })
            ->make(true);
    }
}
