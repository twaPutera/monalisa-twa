<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerpanjanganPeminjamanAsset extends Model
{
    use HasFactory, Uuid;

    public function peminjaman_asset()
    {
        return $this->belongsTo(PeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }
}
