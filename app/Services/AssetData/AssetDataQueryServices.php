<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Models\SistemConfig;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelpers;
use App\Models\PeminjamanAsset;
use App\Models\GroupKategoriAsset;
use App\Models\PerencanaanServices;
use App\Models\DetailPemindahanAsset;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use Illuminate\Support\Facades\DB;

class AssetDataQueryServices
{
    protected $userSsoQueryServices;
    protected $userQueryServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function findImageById(string $id)
    {
        $find = AssetImage::find($id);
        if (isset($find)) {
            $find->link = route('admin.listing-asset.image.preview') . '?filename=' . $find->path;
        }
        return $find;
    }
    public function findAll(Request $request)
    {
        $query = AssetData::query();

        if (isset($request->relations)) {
            $query->with($request->relations);
        }

        if (isset($request->selects)) {
            $query->select($request->selects);
        }

        if (isset($request->id_satuan_asset)) {
            $query->where('id_satuan_asset', $request->id_satuan_asset);
        }

        if (isset($request->id_vendor)) {
            $query->where('id_vendor', $request->id_vendor);
        }

        if (isset($request->id_lokasi) && $request->id_lokasi != 'root') {
            $query->where('id_lokasi', $request->id_lokasi);
        }

        if (isset($request->id_kelas_asset)) {
            $query->where('id_kelas_asset', $request->id_kelas_asset);
        }

        if (isset($request->id_kategori_asset)) {
            $query->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->categories)) {
            $query->whereIn('id_kategori_asset', $request->categories);
        }

        if (isset($request->is_sparepart)) {
            $query->where('is_sparepart', $request->is_sparepart);
        }

        if (isset($request->is_draft)) {
            $query->where('is_draft', $request->is_draft);
        }

        if (isset($request->status_kondisi)) {
            if ($request->status_kondisi != 'semua') {
                $query->where('status_kondisi', $request->status_kondisi);
            }
        }

        if (isset($request->statusArray)) {
            $query->whereIn('status_kondisi', $request->statusArray);
        }

        if (isset($request->jenis)) {
            $query->where('id_kategori_asset', $request->jenis);
        }

        if (isset($request->is_pinjam)) {
            $query->where('is_pinjam', $request->is_pinjam);
        }

        if (isset($request->is_pemutihan)) {
            $query->where('is_pemutihan', $request->is_pemutihan);
        }

        $query->orderBy('created_at', 'desc');

        $data = $query->get();

        return $data;
    }

    public function findById(string $id, array $request = [])
    {
        $data =  AssetData::query()
            ->with([
                'satuan_asset',
                'vendor',
                'lokasi',
                'kelas_asset',
                'kategori_asset.group_kategori_asset',
                'image',
                'detail_service',
                'unit_kerja',
                'log_asset_opname' => function ($query) {
                    $query->orderBy('created_at', 'desc'); // Urutkan berdasarkan created_at descending
                },
            ])
            ->where('id', $id)
            ->firstOrFail();
        if (is_null($data->qr_code)) {
            $qr_name = 'qr-asset-' . $data->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($data->kode_asset, $path);
            $data->qr_code = $qr_name;
            $data->save();
        }
        $user = null;
        $nama_unit_kerja=null;
        $created_by = null;
        if (isset($data->ownership)) {
            if (config('app.sso_siska')) {
                // $user = $this->userSsoQueryServices->findById($data->ownership);
                $user = $this->userSsoQueryServices->getUserByGuid($data->ownership);
                $user = isset($user[0]) ? collect($user[0]) : null;
            } else {
                $user = $this->userQueryServices->findById($data->ownership);
            }
        }

        if (isset($data->id_unit_kerja)) {
            $nama_unit_kerja = $this->userQueryServices->findUnitKerjaById($data->id_unit_kerja);
        }

        if ($data->log_asset_opname->count() > 0) {
            if (config('app.sso_siska')) {
                $created_by = $this->userSsoQueryServices->getUserByGuid($data->log_asset_opname->sortByDesc('created_at')->first()->created_by);
                $created_by = isset($created_by[0]) ? collect($created_by[0]) : null;
            } else {
                $created_by = $this->userQueryServices->findById($data->log_asset_opname->sortByDesc('created_at')->first()->created_by);
            }
        }
        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.image.preview') . '?filename=' . $item->path;
            return $item;
        });

        if (isset($request['peminjaman'])) {
            $peminjaman = PeminjamanAsset::query()
                ->wherehas('detail_peminjaman_asset', function ($query) use ($id) {
                    $query->where('id_asset', $id);
                })
                ->where(function ($query) use ($id) {
                    $query->where('status', 'dipinjam')
                        ->orWhere('status', 'diproses');
                })
                ->first();
            $data->peminjam = null;
            if (isset($peminjaman)) {
                $peminjam = json_decode($peminjaman->json_peminjam_asset);
                $data->peminjam = $peminjam;
            }
        }

        $data->link_detail = route('admin.listing-asset.detail', $data->id);
        $data->owner_name = $user == null ? 'Tidak ada' : $user->name ?? $user->nama;
        $data->owner = $user;
        $data->nama_unit_kerja =$nama_unit_kerja == null ? 'Tidak ada' : $nama_unit_kerja;
        $data->created_by_opname = $created_by == null ? 'Tidak Ada' : $created_by->name ?? $created_by->nama;
        return $data;
    }

    public function findBykode(Request $request)
    {
        $data =  AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image'])
            ->where('kode_asset', $request->kode_asset)
            ->first();
        return $data;
    }

    public function findAssetImageById(string $id)
    {
        return AssetImage::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getDataAssetSelect2(Request $request)
    {
        $data = AssetData::query();

        if (isset($request->keyword)) {
            // $data->where('deskripsi', 'like', '%' . $request->keyword . '%')
            //     ->where(function ($query) use ($request) {
            //         $query->orWhere('kode_asset', 'like', '%' . $request->keyword . '%');
            //     });
            $data->where('deskripsi', 'like', '%' . $request->keyword . '%')
                ->orWhere('kode_asset', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->id_kategori_asset)) {
            $data->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->id_lokasi)) {
            if ($request->id_lokasi != 'root') {
                $data->where('id_lokasi', $request->id_lokasi);
            }
        }

        if (isset($request->id_asset)) {
            $perencanaan = PerencanaanServices::where('id', $request->id_asset)->first();
            if ($perencanaan) {
                $data->where('id', $perencanaan->id_asset_data);
            }
        }

        $user = SsoHelpers::getUserLogin();
        if (!isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $data->where('is_it', 1);
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $data->where('is_it', 0);
                }
            }
        }

        $data->where('is_pemutihan', '0'); //To get all data asset is not pemutihan
        $data->where('is_draft', '0'); // get all data where is publish
        $data = $data->orderby('deskripsi', 'asc')
            ->get();
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->deskripsi . ' (' . $item->kode_asset . ')',
            ];
        }

        return $results;
    }

    public function getDataAssetForDashboardUser(string $user_id)
    {
        $asset_by_ownership = AssetData::query()
            ->select([
                'id',
            ])
            ->where('ownership', $user_id)
            ->get()->toArray();

        $asset_from_pemindahan = DetailPemindahanAsset::query()
            ->select([
                'id_asset',
            ])
            ->whereHas('pemindahan_asset', function ($query) use ($user_id) {
                $query->where('guid_penerima_asset', $user_id)
                    ->where('status', 'pending');
            })
            ->get()->toArray();

        // * Tambah Query Peminjaman Asset

        $array_id_asset = \Arr::flatten(array_merge($asset_by_ownership, $asset_from_pemindahan));

        $asset_data = AssetData::query()
            ->select([
                'id',
                'kode_asset',
                'deskripsi',
                'tgl_register',
                'id_kategori_asset',
            ])
            ->with(['kategori_asset.group_kategori_asset'])
            ->whereIn('id', $array_id_asset)
            ->get();

        return $asset_data;
    }

    public function checkIsAssetOnPemindahanAsset(string $asset_id, string $user_id)
    {
        $asset_from_pemindahan = DetailPemindahanAsset::query()
            ->select([
                'id',
                'id_pemindahan_asset',
                'id_asset',
            ])
            ->with(['pemindahan_asset'])
            ->whereHas('pemindahan_asset', function ($query) use ($user_id) {
                $query->where('guid_penerima_asset', $user_id)
                    ->where('status', 'pending');
            })
            ->where('id_asset', $asset_id)
            ->first();

        return $asset_from_pemindahan;
    }

    public function countAsset(Request $request)
    {
        $data = AssetData::query();

        if (isset($request->id_kategori_asset)) {
            $data->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->id_lokasi)) {
            if ($request->id_lokasi != 'root') {
                $data->where('id_lokasi', $request->id_lokasi);
            }
        }

        if (isset($request->id_vendor)) {
            $data->where('id_vendor', $request->id_vendor);
        }

        if (isset($request->id_kelas_asset)) {
            $data->where('id_kelas_asset', $request->id_kelas_asset);
        }

        if (isset($request->id_satuan_asset)) {
            $data->where('id_satuan_asset', $request->id_satuan_asset);
        }

        if (isset($request->id_group_kategori_asset)) {
            $data->whereHas('kategori_asset', function ($query) use ($request) {
                $query->where('id_group_kategori_asset', $request->id_group_kategori_asset);
            });
        }

        if (isset($request->is_pemutihan)) {
            $data->where('is_pemutihan', $request->is_pemutihan);
        }

        if (isset($request->is_draft)) {
            $data->where('is_draft', $request->is_draft);
        }

        if (isset($request->awal)) {
            $data->where('tgl_register', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $data->where('tgl_register', '<=', $request->akhir);
        }

        if (isset($request->status_asset)) {
            $data->where('status_asset', $request->status_asset);
        }

        $data = $data->count();

        return $data;
    }

    public function lastUpdateAsset(Request $request)
    {
        $data = AssetData::query();

        if (isset($request->awal)) {
            $data->where('tgl_register', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $data->where('tgl_register', '<=', $request->akhir);
        }
        $data->max('updated_at');

        return $data;
    }

    public function getValueAsset(Request $request)
    {
        if (isset($request->awal) && !isset($request->akhir)) {
            $nilai_beli_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('tgl_register', '>=', $request->awal)
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_perolehan');

            $nilai_value_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('tgl_register', '>=', $request->awal)
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_buku_asset');
        } elseif (!isset($request->awal) && isset($request->akhir)) {
            $nilai_beli_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('tgl_register', '<=', $request->akhir)
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_perolehan');

            $nilai_value_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('tgl_register', '<=', $request->akhir)
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_buku_asset');
        } elseif (isset($request->awal) && isset($request->akhir)) {
            $nilai_beli_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('tgl_register', '>=', $request->awal)
                ->where('tgl_register', '<=', $request->akhir)
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_perolehan');

            $nilai_value_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('tgl_register', '>=', $request->awal)
                ->where('tgl_register', '<=', $request->akhir)
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_buku_asset');
        } else {
            $nilai_beli_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_perolehan');

            $nilai_value_asset = AssetData::query()
                ->where('is_pemutihan', '0')
                ->where('is_draft', '0')
                ->where('status_kondisi', '!=', 'pengembangan')
                ->sum('nilai_buku_asset');
        }

        $nilai_depresiasi = $nilai_beli_asset - $nilai_value_asset;

        return [
            'nilai_beli_asset' => (int) $nilai_beli_asset,
            'nilai_value_asset' => (int) $nilai_value_asset,
            'nilai_depresiasi' => (int) $nilai_depresiasi,
        ];
    }

    public function getDataChartSummaryAssetByGroup(Request $request)
    {
        $data = [];
        $group_kategori_asset = GroupKategoriAsset::query()
            ->select([
                'id',
                'nama_group',
            ])
            ->get();

        foreach ($group_kategori_asset as $item) {
            if (isset($request->awal) && !isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->whereHas('kategori_asset', function ($query) use ($item) {
                        $query->where('id_group_kategori_asset', $item->id);
                    })
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->where('tgl_register', '>=', $request->awal)
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->count();
            } elseif (!isset($request->awal) && isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->whereHas('kategori_asset', function ($query) use ($item) {
                        $query->where('id_group_kategori_asset', $item->id);
                    })
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->where('tgl_register', '<=', $request->akhir)
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->count();
            } elseif (isset($request->awal) && isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->whereHas('kategori_asset', function ($query) use ($item) {
                        $query->where('id_group_kategori_asset', $item->id);
                    })
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->where('tgl_register', '>=', $request->awal)
                    ->where('tgl_register', '<=', $request->akhir)
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->count();
            } else {
                $count_asset = AssetData::query()
                    ->whereHas('kategori_asset', function ($query) use ($item) {
                        $query->where('id_group_kategori_asset', $item->id);
                    })
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->count();
            }

            $data[] = [
                'name' => $item->nama_group,
                'value' => $count_asset,
            ];
        }

        return $data;
    }

    public function getDataChartNilaiBukuByGroup(Request $request)
    {
        $data = [];
        $group_kategori_asset = GroupKategoriAsset::query()
            ->select([
                'id',
                'nama_group',
            ])
            ->get();

        $user = SsoHelpers::getUserLogin();
        foreach ($group_kategori_asset as $item) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    if (isset($request->awal) && !isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->where('tgl_register', '>=', $request->awal)
                            ->sum('nilai_buku_asset');
                    } elseif (!isset($request->awal) && isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->where('tgl_register', '<=', $request->akhir)
                            ->sum('nilai_buku_asset');
                    } elseif (isset($request->awal) && isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('tgl_register', '<=', $request->akhir)
                            ->sum('nilai_buku_asset');
                    } else {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_buku_asset');
                    }
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    if (isset($request->awal) && !isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->where('tgl_register', '>=', $request->awal)
                            ->sum('nilai_buku_asset');
                    } elseif (!isset($request->awal) && isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->where('tgl_register', '<=', $request->akhir)
                            ->sum('nilai_buku_asset');
                    } elseif (isset($request->awal) && isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('tgl_register', '<=', $request->akhir)
                            ->sum('nilai_buku_asset');
                    } else {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_buku_asset');
                    }
                } else {
                    if (isset($request->awal) && !isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_buku_asset');
                    } elseif (!isset($request->awal) && isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_buku_asset');
                    } elseif (isset($request->awal) && isset($request->akhir)) {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_buku_asset');
                    } else {
                        $nilai_buku = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_buku_asset');
                    }
                }
            }

            $data[] = [
                'name' => $item->nama_group,
                'value' => $nilai_buku,
            ];
        }

        return $data;
    }

    public function getDataChartNilaiPerolehanByGroup(Request $request)
    {
        $data = [];
        $group_kategori_asset = GroupKategoriAsset::query()
            ->select([
                'id',
                'nama_group',
            ])
            ->get();

        $user = SsoHelpers::getUserLogin();
        foreach ($group_kategori_asset as $item) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    if (isset($request->awal) && !isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } elseif (!isset($request->awal) && isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } elseif (isset($request->awal) && isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } else {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '1')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    }
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    if (isset($request->awal) && !isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } elseif (!isset($request->awal) && isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } elseif (isset($request->awal) && isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } else {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_it', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    }
                } else {
                    if (isset($request->awal) && !isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } elseif (!isset($request->awal) && isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } elseif (isset($request->awal) && isset($request->akhir)) {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('tgl_register', '>=', $request->awal)
                            ->where('tgl_register', '<=', $request->akhir)
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    } else {
                        $nilai_perolehan = AssetData::query()
                            ->whereHas('kategori_asset', function ($query) use ($item) {
                                $query->where('id_group_kategori_asset', $item->id);
                            })
                            ->where('is_pemutihan', '0')
                            ->where('is_draft', '0')
                            ->where('status_kondisi', '!=', 'pengembangan')
                            ->sum('nilai_perolehan');
                    }
                }
            }

            $data[] = [
                'name' => $item->nama_group,
                'value' => $nilai_perolehan,
            ];
        }

        return $data;
    }

    public function getDataChartSummaryAssetByStatus(Request $request)
    {
        $status = ['bagus', 'rusak', 'maintenance', 'tidak-lengkap', 'pengembangan'];
        $data = [];
        foreach ($status as $item) {
            if (isset($request->awal) && !isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->where('status_kondisi', $item)
                    ->where('is_pemutihan', '0')
                    ->where('updated_at', '>=', $request->awal . ' 00:00:00')
                    ->where('is_draft', '0')
                    ->count();
            } elseif (!isset($request->awal) && isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->where('status_kondisi', $item)
                    ->where('is_pemutihan', '0')
                    ->where('updated_at', '<=', $request->akhir . ' 00:00:00')
                    ->where('is_draft', '0')
                    ->count();
            } elseif (isset($request->awal) && isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->where('status_kondisi', $item)
                    ->where('is_pemutihan', '0')
                    ->where('updated_at', '>=', $request->awal . ' 00:00:00')
                    ->where('updated_at', '<=', $request->akhir . ' 23:59:00')
                    ->where('is_draft', '0')
                    ->count();
            } else {
                $count_asset = AssetData::query()
                    ->where('status_kondisi', $item)
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->count();
            }
            $data[] = [
                'name' => $item,
                'value' => $count_asset,
            ];
        }

        return $data;
    }

    public function getDataChartSummaryAssetByMonthRegister(Request $request)
    {
        $data = [];
        $month = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Ags',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ];
        foreach ($month as $key => $item) {
            if (isset($request->awal) && !isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->whereMonth('tgl_register', $key + 1)
                    ->whereYear('tgl_register', date('Y'))
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->where('tgl_register', '>=', $request->awal)
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->count();
            } elseif (!isset($request->awal) && isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->whereMonth('tgl_register', $key + 1)
                    ->whereYear('tgl_register', date('Y'))
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->where('tgl_register', '<=', $request->akhir)
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->count();
            } elseif (isset($request->awal) && isset($request->akhir)) {
                $count_asset = AssetData::query()
                    ->whereMonth('tgl_register', $key + 1)
                    ->whereYear('tgl_register', date('Y'))
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->where('tgl_register', '>=', $request->awal)
                    ->where('tgl_register', '<=', $request->akhir)
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->count();
            } else {
                $count_asset = AssetData::query()
                    ->whereMonth('tgl_register', $key + 1)
                    ->whereYear('tgl_register', date('Y'))
                    ->where('status_kondisi', '!=', 'pengembangan')
                    ->where('is_pemutihan', '0')
                    ->where('is_draft', '0')
                    ->count();
            }

            $data['name'][] = $item;
            $data['value'][] = $count_asset;
        }

        return $data;
    }

    public function getAvgDepresiasiAsset()
    {
        $asset = AssetData::query()
            ->select([
                'id',
                'nilai_perolehan',
                'nilai_buku_asset',
            ])
            ->where('is_pemutihan', '0')
            ->where('is_draft', '0')
            ->where('status_kondisi', '!=', 'pengembangan')
            ->where('nilai_buku_asset', '>', '0')
            ->get();

        $avg_depresiiasi = 0;

        foreach ($asset as $item) {
            $avg_depresiiasi += $item->nilai_buku_asset / $item->nilai_perolehan;
        }

        $avg_depresiiasi = $avg_depresiiasi / $asset->count();

        return $avg_depresiiasi * 100;
    }

    public function getMaxValueNoUrutAssetByKelompokId(string $id, string $id_asset = null)
    {
        // $asset = AssetData::query()
        //     ->where('id_kategori_asset', $id)
        //     ->whereRaw('no_urut REGEXP "^([,|.]?[0-9])+$"')
        //     ->max('no_urut');

        //query update dari wahyu
        $asset = AssetData::query()
            ->where('id_kategori_asset', $id)
            ->whereRaw('no_urut REGEXP ?', ['^([,|.]?[0-9])+$'])
            ->max(DB::raw('CAST(no_urut AS SIGNED)'));

        $no_urut_config = SistemConfig::query()
            ->where('config', 'min_no_urut')
            ->first();

        $config = $no_urut_config->value ?? 5;

        $no = 1;

        if (isset($asset)) {
            $no = $asset + 1;

          
            if ($id_asset != null) {
                $plus_one = AssetData::where('id', $id_asset)
                    ->where('id_kategori_asset', $id)
                    ->where('no_urut', $asset)
                    ->first();
                if ($plus_one) {
                    $no = $asset;
                }
            }
        }

        $no_urut = str_pad($no, $config, '0', STR_PAD_LEFT);
        return $no_urut;
    }
}
