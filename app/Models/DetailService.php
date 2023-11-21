<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailService extends Model
{
    use HasFactory, Uuid;

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id');
    }

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset_data', 'id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id');
    }
}
