<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogPeminjamanAsset extends Model
{
    use HasFactory, Uuid;

    public function peminjaman_asset()
    {
        return $this->belongsTo(PeminjamanAsset::class, 'peminjaman_asset_id');
    }
}
