<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerencanaanServices extends Model
{
    use HasFactory, Uuid;

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset_data', 'id');
    }

    public function log_asset_opaname()
    {
        return $this->belongsTo(LogAssetOpname::class, 'id_log_opname', 'id');
    }
}
