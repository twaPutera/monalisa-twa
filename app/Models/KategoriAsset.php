<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriAsset extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public function group_kategori_asset()
    {
        return $this->belongsTo(GroupKategoriAsset::class, 'id_group_kategori_asset', 'id');
    }

    public function asset_data()
    {
        return $this->hasMany(AssetData::class, 'id_kategori_asset', 'id');
    }
}
