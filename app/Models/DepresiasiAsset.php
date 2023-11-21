<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepresiasiAsset extends Model
{
    use HasFactory, Uuid;

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset_data');
    }
}
