<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamanAsset extends Model
{
    use HasFactory, Uuid;

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    public function detail_peminjaman_asset()
    {
        return $this->hasMany(DetailPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function perpanjangan_peminjaman_asset()
    {
        return $this->hasMany(PerpanjanganPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function request_peminjaman_asset()
    {
        return $this->hasMany(RequestPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function log_peminjaman_asset()
    {
        return $this->hasMany(LogPeminjamanAsset::class, 'peminjaman_asset_id', 'id');
    }

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'guid_peminjam_asset', 'id');
    }
}
