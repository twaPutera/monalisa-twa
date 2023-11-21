<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogServiceAsset extends Model
{
    use HasFactory, Uuid;

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id');
    }
}
