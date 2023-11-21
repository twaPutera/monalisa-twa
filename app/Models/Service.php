<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, Uuid;

    public function detail_service()
    {
        return $this->hasOne(DetailService::class, 'id_service', 'id');
    }

    public function kategori_service()
    {
        return $this->belongsTo(KategoriService::class, 'id_kategori_service', 'id');
    }

    public function image()
    {
        return $this->morphMany(AssetImage::class, 'imageable');
    }

    public function log_service()
    {
        return $this->hasMany(LogServiceAsset::class, 'id_service', 'id');
    }
}
