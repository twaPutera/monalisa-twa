<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAsset extends Model
{
    use HasFactory, Uuid;

    public function asset()
    {
        return $this->belongsTo(AssetData::class, 'asset_id');
    }
}
