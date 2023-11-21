<?php

namespace App\Services\Pengaduan;

use App\Models\User;
use App\Models\AssetData;
use App\Models\Pengaduan;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\LogPengaduanAsset;
use App\Notifications\UserNotification;
use App\Http\Requests\Pengaduan\PengaduanStoreRequest;
use App\Http\Requests\Pengaduan\PengaduanUpdateRequest;
use App\Http\Requests\Pengaduan\AssetPengaduanStoreRequest;

class PengaduanCommandServices
{
    public function storeUserFromScan(AssetPengaduanStoreRequest $request, string $id)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_pengaduan = new Pengaduan();
        $asset_data = AssetData::where('is_pemutihan', 0)
            ->where('is_draft', '0')
            ->where('id', $id)->first();
        $asset_pengaduan->kode_pengaduan =  self::generateCode();
        $asset_pengaduan->id_asset_data = $asset_data->id;
        $asset_pengaduan->id_lokasi = $asset_data->lokasi->id ?? null;
        $asset_pengaduan->tanggal_pengaduan  = $request->tanggal_pengaduan;
        $asset_pengaduan->catatan_pengaduan = $request->alasan_pengaduan;
        $asset_pengaduan->status_pengaduan = 'dilaporkan';
        $asset_pengaduan->prioritas = $request->prioritas;
        $asset_pengaduan->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_pengaduan->save();

        if ($asset_data->is_it == 1) {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
        }

        $notifikasi = [
            'title' => 'Pengaduan Asset',
            'message' => 'Pengaduan Asset dengan kode ' . $asset_pengaduan->kode_pengaduan . ' telah dilaporkan oleh ' . $user->name,
            'url' => route('admin.keluhan.index', ['pengaduan_id' => $asset_pengaduan->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        $log = self::storeLog($asset_pengaduan->id, 'dilaporkan', $request->alasan_pengaduan, 'Laporan Masuk');

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_pengaduan->id);
            $path = storage_path('app/images/asset-pengaduan');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_pengaduan);
            $asset_images->imageable_id = $asset_pengaduan->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
    }
    private static function generateCode()
    {
        $code = 'PAA-' . date('Ymd') . '-' . rand(1000, 9999);
        $check_code = Pengaduan::where('kode_pengaduan', $code)->first();

        if ($check_code) {
            return self::generateCode();
        }

        return $code;
    }

    public function storeUserPengaduan(PengaduanStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_pengaduan = new Pengaduan();
        if (! empty($request->id_asset)) {
            $asset_data = AssetData::where('is_pemutihan', 0)
                ->where('is_draft', '0')
                ->where('id', $request->id_asset)->first();
            $asset_pengaduan->id_asset_data = $asset_data->id;

            if ($asset_data->is_it == 1) {
                $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
            } else {
                $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
            }
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'manager_asset', 'staff_it', 'staff_asset'])->get();
        }

        $asset_pengaduan->kode_pengaduan =  self::generateCode();
        $asset_pengaduan->id_lokasi = $request->id_lokasi;
        $asset_pengaduan->tanggal_pengaduan  = $request->tanggal_pengaduan;
        $asset_pengaduan->catatan_pengaduan = $request->alasan_pengaduan;
        $asset_pengaduan->status_pengaduan = 'dilaporkan';
        $asset_pengaduan->prioritas = $request->prioritas;
        $asset_pengaduan->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_pengaduan->save();

        $notifikasi = [
            'title' => 'Pengaduan Asset',
            'message' => 'Pengaduan Asset dengan kode ' . $asset_pengaduan->kode_pengaduan . ' telah dilaporkan oleh ' . $user->name,
            'url' => route('admin.keluhan.index', ['pengaduan_id' => $asset_pengaduan->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        $log = self::storeLog($asset_pengaduan->id, 'dilaporkan', $request->alasan_pengaduan, 'Laporan Masuk');

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_pengaduan->id);
            $path = storage_path('app/images/asset-pengaduan');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_pengaduan);
            $asset_images->imageable_id = $asset_pengaduan->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
    }

    public function updateUserPengaduan(PengaduanUpdateRequest $request, string $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $asset_pengaduan = Pengaduan::findOrFail($id);
        if (! empty($request->id_asset)) {
            $asset_data = AssetData::where('is_pemutihan', 0)
                ->where('is_draft', '0')
                ->where('id', $request->id_asset)->first();
            $asset_pengaduan->id_asset_data = $asset_data->id;
            if ($asset_data->is_it == 1) {
                $target_user = User::whereIn('role', ['admin', 'manager_it', 'staff_it'])->get();
            } else {
                $target_user = User::whereIn('role', ['admin', 'manager_asset', 'staff_asset'])->get();
            }
        } else {
            $target_user = User::whereIn('role', ['admin', 'manager_it', 'manager_asset', 'staff_it', 'staff_asset'])->get();
        }

        $asset_pengaduan->id_lokasi = $request->id_lokasi;
        $asset_pengaduan->tanggal_pengaduan  = $request->tanggal_pengaduan;
        $asset_pengaduan->catatan_pengaduan = $request->alasan_pengaduan;
        $asset_pengaduan->status_pengaduan = 'dilaporkan';
        $asset_pengaduan->prioritas = $request->prioritas;
        $asset_pengaduan->save();

        $notifikasi = [
            'title' => 'Pengaduan Asset',
            'message' => 'Pengaduan Asset dengan kode ' . $asset_pengaduan->kode_pengaduan . ' telah diubah dan dilaporkan ulang oleh ' . $user->name,
            'url' => route('admin.keluhan.index', ['pengaduan_id' => $asset_pengaduan->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        $log = self::storeLog($asset_pengaduan->id, 'dilaporkan', $request->alasan_pengaduan, 'Perubahan Laporan');

        if ($request->hasFile('file_asset_service')) {
            $path = storage_path('app/images/asset-pengaduan');
            if (isset($asset_pengaduan->image[0])) {
                $pathOld = $path . '/' . $asset_pengaduan->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset_pengaduan->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_pengaduan->id);
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_pengaduan);
            $asset_images->imageable_id = $asset_pengaduan->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-pengaduan-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }

    public function destroy(string $id)
    {
        $pengaduan = Pengaduan::with(['image'])->where('status_pengaduan', 'dilaporkan')->where('id', $id)->first();
        $path = storage_path('app/images/asset-pengaduan');
        if (isset($pengaduan->image[0])) {
            $pathOld = $path . '/' . $pengaduan->image[0]->path;
            FileHelpers::removeFile($pathOld);
            $pengaduan->image[0]->delete();
        }
        $log_pengaduan = LogPengaduanAsset::where('id_pengaduan', $pengaduan->id)->get();
        foreach ($log_pengaduan as $item) {
            $item->delete();
        }
        return $pengaduan->delete();
    }

    protected static function storeLog($id_pengaduan, $status, $message, $action)
    {
        $log_asset = new LogPengaduanAsset();
        $user = SsoHelpers::getUserLogin();
        $log_asset->id_pengaduan = $id_pengaduan;
        $log_asset->message_log = "$action Data Pengaduan Asset/Lokasi (Keterangan: $message)";
        $log_asset->status =  $status;
        $log_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log_asset->save();

        return $log_asset;
    }
}
