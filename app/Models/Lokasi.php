<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lokasi extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public function asset_data()
    {
        return $this->hasMany(AssetData::class, 'id_lokasi', 'id');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_lokasi', 'id');
    }

    public function log_asset_opname()
    {
        return $this->hasMany(LogAssetOpname::class, 'id_lokasi', 'id');
    }
}
