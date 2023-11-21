<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPemutihanAsset extends Model
{
    use HasFactory, Uuid;

    public function pemutihan_asset()
    {
        return $this->belongsTo(PemutihanAsset::class, 'id_pemutihan_asset', 'id');
    }

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset_data', 'id');
    }

    public function image()
    {
        return $this->morphMany(AssetImage::class, 'imageable');
    }
}
