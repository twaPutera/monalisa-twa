<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPeminjamanAsset extends Model
{
    use HasFactory, Uuid;

    public function peminjaman_asset()
    {
        return $this->belongsTo(PeminjamanAsset::class, 'id_peminjaman_asset');
    }

    public function asset()
    {
        return $this->belongsTo(AssetData::class, 'id_asset');
    }
}
