<?php

namespace App\Services\AssetService;

use App\Models\User;
use App\Models\Service;
use App\Helpers\CutText;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\DetailService;
use App\Models\LogServiceAsset;
use App\Models\PerencanaanServices;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use App\Http\Requests\Services\ServicesStoreRequest;
use App\Http\Requests\Services\ServicesUpdateRequest;
use App\Http\Requests\Services\ServicesUpdateStatusRequest;
use App\Http\Requests\AssetService\AssetServiceStoreRequest;
use App\Http\Requests\UserAssetService\UserAssetServiceStoreRequest;

class AssetServiceCommandServices
{
    public function store(string $id, AssetServiceStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('is_it', '1')
                        ->where('id', $id)->first();
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('is_it', '0')
                        ->where('id', $id)->first();
                } else {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('id', $id)->first();
                }
            }
        } else {
            $asset_data = AssetData::where('is_pemutihan', 0)
                ->where('is_draft', '0')
                ->where('id', $id)->first();
        }

        if ($request->select_service_date == 'baru') {
            $tanggal_mulai = $request->tanggal_mulai_service;
        } else {
            $perencanaan = PerencanaanServices::where('id', $request->tanggal_mulai_perencanaan)->where('status', 'pending')->first();
            $perencanaan->status = 'realisasi';
            $perencanaan->save();

            $tanggal_mulai = $perencanaan->tanggal_perencanaan;
        }
        $asset_service = new Service();
        $asset_service->kode_services =  self::generateCode();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $tanggal_mulai;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->save();

        if ($asset_data->is_it == '1') {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
        }

        if ($request->status_service == 'selesai') {
            $asset_data->status_kondisi = $request->status_kondisi == 'baik' ? 'bagus' : $request->status_kondisi;
            $asset_data->save();
        } else {
            $asset_data->status_kondisi = 'maintenance';
            $asset_data->save();
        }

        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        $log = self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service, 'Penambahan', $request->keterangan_service);

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        $notifikasi = [
            'title' => 'Service Asset',
            'message' => 'Service Asset dengan kode ' . $asset_service->kode_services . ' telah dibuat oleh ' . $user->name,
            'url' => route('admin.services.index', ['service_id' => $asset_service->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        return $asset_service;
    }
    private static function generateCode()
    {
        $code = 'ADS-' . date('Ymd') . '-' . rand(1000, 9999);
        $check_code = Service::where('kode_services', $code)->first();

        if ($check_code) {
            return self::generateCode();
        }

        return $code;
    }
    public function storeServices(ServicesStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('is_it', '1')
                        ->where('id', $request->id_asset)->first();
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('is_it', '0')
                        ->where('id', $request->id_asset)->first();
                } else {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('id', $request->id_asset)->first();
                }
            }
        } else {
            $asset_data = AssetData::where('is_pemutihan', 0)
                ->where('is_draft', '0')
                ->where('id', $request->id_asset)->first();
        }

        if ($asset_data->is_it == '1') {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
        }

        if ($request->select_service_date == 'baru') {
            $tanggal_mulai = $request->tanggal_mulai_service;
        } else {
            $perencanaan = PerencanaanServices::where('id', $request->tanggal_mulai_perencanaan)->where('status', 'pending')->first();
            $perencanaan->status = 'realisasi';
            $perencanaan->save();

            $tanggal_mulai = $perencanaan->tanggal_perencanaan;
        }
        $asset_service = new Service();
        $asset_service->kode_services =  self::generateCode();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $tanggal_mulai;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->save();

        if ($request->status_service == 'selesai') {
            $asset_data->status_kondisi = $request->status_kondisi == 'baik' ? 'bagus' : $request->status_kondisi;
            $asset_data->save();
        } else {
            $asset_data->status_kondisi = 'maintenance';
            $asset_data->save();
        }
        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service, 'Penambahan', $request->keterangan_service);

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        $notifikasi = [
            'title' => 'Service Asset',
            'message' => 'Service Asset dengan kode ' . $asset_service->kode_services . ' telah dibuat oleh ' . $user->name,
            'url' => route('admin.services.index', ['service_id' => $asset_service->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        return $asset_service;
    }

    public function storeUserServices(UserAssetServiceStoreRequest $request, string $id)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('is_it', '1')
                        ->where('id', $id)->first();
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('is_it', '0')
                        ->where('id', $id)->first();
                } else {
                    $asset_data = AssetData::where('is_pemutihan', 0)
                        ->where('is_draft', '0')
                        ->where('id', $id)->first();
                }
            }
        } else {
            $asset_data = AssetData::where('is_pemutihan', 0)
                ->where('is_draft', '0')
                ->where('id', $id)->first();
        }

        if ($asset_data->is_it == '1') {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
        }

        if ($request->select_service_date == 'baru') {
            $tanggal_mulai = $request->tanggal_mulai_service;
        } else {
            $perencanaan = PerencanaanServices::where('id', $request->tanggal_mulai_perencanaan)->where('status', 'pending')->first();
            $perencanaan->status = 'realisasi';
            $perencanaan->save();

            $tanggal_mulai = $perencanaan->tanggal_perencanaan;
        }
        $asset_service = new Service();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_mulai = $tanggal_mulai;
        $asset_service->kode_services =  self::generateCode();
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->save();

        if ($request->status_service == 'selesai') {
            $asset_data->status_kondisi = $request->status_kondisi == 'baik' ? 'bagus' : $request->status_kondisi;
            $asset_data->save();
        } else {
            $asset_data->status_kondisi = 'maintenance';
            $asset_data->save();
        }

        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $asset_data->id;
        $detail_asset_service->id_lokasi = $asset_data->id_lokasi;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->permasalahan = $request->permasalahan;
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();

        self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service, 'Penambahan', $request->keterangan_service);

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        $notifikasi = [
            'title' => 'Service Asset',
            'message' => 'Service Asset dengan kode ' . $asset_service->kode_services . ' telah dibuat oleh ' . $user->name,
            'url' => route('admin.services.index', ['service_id' => $asset_service->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }
        return $asset_service;
    }

    public function updateServices(string $id, ServicesUpdateRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $asset_service = Service::findOrFail($id);

        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->save();

        $detail_asset_service = DetailService::where('id_service', $asset_service->id)->firstOrFail();
        $asset_data = AssetData::where('id', $detail_asset_service->id_asset_data)->first();
        $detail_asset_service->tindakan = $request->tindakan;
        $detail_asset_service->catatan = $request->catatan;
        $detail_asset_service->save();
        self::storeLog($asset_service->id, $asset_data->deskripsi, $asset_service->status_service, 'Perubahan', 'Perubahan Informasi Services');

        if ($asset_data->is_it == '1') {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
        }

        $notifikasi = [
            'title' => 'Service Asset',
            'message' => 'Service Asset dengan kode ' . $asset_service->kode_services . ' telah diperbaharui oleh ' . $user->name,
            'url' => route('admin.services.index', ['service_id' => $asset_service->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        return $detail_asset_service;
    }

    public function updateStatusServices(string $id, ServicesUpdateStatusRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $asset_service = Service::findOrFail($id);

        $asset_service->guid_pembuat = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_service->tanggal_selesai = $request->tanggal_selesai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'on progress' : $request->status_service;
        $asset_service->status_kondisi = $request->status_kondisi;
        $asset_service->save();

        $detail_asset_service = DetailService::where('id_service', $asset_service->id)->firstOrFail();
        $asset_data = AssetData::where('id', $detail_asset_service->id_asset_data)->first();
        if ($request->status_service == 'selesai') {
            $asset_data->status_kondisi = $request->status_kondisi == 'baik' ? 'bagus' : $request->status_kondisi;
            $asset_data->save();
        } else {
            $asset_data->status_kondisi = 'maintenance';
            $asset_data->save();
        }

        self::storeLog($asset_service->id, $asset_data->deskripsi, $request->status_service, 'Perubahan', $request->keterangan_service);

        if ($request->hasFile('file_asset_service')) {
            $path = storage_path('app/images/asset-service');
            if (isset($asset_service->image[0])) {
                $pathOld = $path . '/' . $asset_service->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset_service->image[0]->delete();
            }
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        if ($asset_data->is_it == '1') {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
        }

        $notifikasi = [
            'title' => 'Service Asset',
            'message' => 'Status service Asset dengan kode ' . $asset_service->kode_services . ' telah diperbaharui oleh ' . $user->name,
            'url' => route('admin.services.index', ['service_id' => $asset_service->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }
        return $asset_service;
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-service-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }

    protected static function storeLog($id_asset, $nama_asset, $status, $log, $keterangan)
    {
        $log_asset = new LogServiceAsset();
        $user = SsoHelpers::getUserLogin();
        $log_asset->id_service = $id_asset;
        $log_asset->message_log = "$log Data Service untuk $nama_asset oleh " . ucWords(CutText::cutUnderscore(Auth::user()->role)) . " (Ket : $keterangan )";
        $log_asset->status = $status == 'onprogress' ? 'on progress' : $status;
        $log_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log_asset->save();
        return $log_asset;
    }
}
