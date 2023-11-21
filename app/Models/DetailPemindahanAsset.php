<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPemindahanAsset extends Model
{
    use HasFactory, Uuid;

    public function pemindahan_asset()
    {
        return $this->belongsTo(PemindahanAsset::class, 'id_pemindahan_asset');
    }

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset', 'id');
    }
}
