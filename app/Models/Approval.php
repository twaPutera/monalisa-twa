<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory, Uuid;

    public function approvable()
    {
        return $this->morphTo();
    }

    public function linkApproval()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return route('user.asset-data.pemindahan.approve', $this->approvable_id);
        } elseif ($this->approvable instanceof PemutihanAsset) {
            return route('admin.pemutihan-asset.show', $this->approvable_id);
        } elseif ($this->approvable instanceof PeminjamanAsset) {
            return route('admin.peminjaman.show', $this->approvable_id);
        } elseif ($this->approvable instanceof PerpanjanganPeminjamanAsset) {
            return route('admin.peminjaman.show', $this->approvable->id_peminjaman_asset);
        } elseif ($this->approvable instanceof RequestInventori) {
            return route('admin.listing-inventaris.request-inventori.show', $this->approvable_id);
        }
    }

    public function linkUpdateApproval()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return route('admin.approval.pemindahan.change-status', $this->approvable_id);
        } elseif ($this->approvable instanceof PemutihanAsset) {
            return route('admin.approval.pemutihan.change-status', $this->approvable_id);
        } elseif ($this->approvable instanceof PeminjamanAsset) {
            return route('admin.approval.peminjaman.change-status', $this->approvable_id);
        } elseif ($this->approvable instanceof PerpanjanganPeminjamanAsset) {
            return route('admin.approval.peminjaman.change-status-perpanjangan', $this->approvable_id);
        } elseif ($this->approvable instanceof RequestInventori) {
            return route('admin.approval.request-inventori.change-status', $this->approvable_id);
        }
    }

    public function approvalType()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return 'Pemindahan Asset';
        } elseif ($this->approvable instanceof PemutihanAsset) {
            return 'Pemutihan Asset';
        } elseif ($this->approvable instanceof PeminjamanAsset) {
            return 'Peminjaman Asset';
        } elseif ($this->approvable instanceof PerpanjanganPeminjamanAsset) {
            return 'Perpanjangan Peminjaman Asset';
        } elseif ($this->approvable instanceof RequestInventori) {
            return 'Request Penggunaan Inventori';
        }

        return 'Tipe Tidak Terdaftar';
    }

    public function getPembuatApproval()
    {
        $userSso = new UserSsoQueryServices();
        $userService = new UserQueryServices();
        $guid = $this->approvable instanceof RequestInventori ? $this->approvable->guid_pengaju : $this->approvable->created_by;
        $name = 'Tidak Terdaftar di Siska';
        if (isset($guid)) {
            if (config('app.sso_siska')) {
                $user = $guid == null ? null : $userSso->getUserByGuid($guid);
                $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            } else {
                $user = $guid == null ? null : $userService->findById($guid);
                $name = isset($user) ? $user->name : 'Not Found';
            }
        }

        return $name;
    }
}
