<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRequestInventori extends Model
{
    use HasFactory, Uuid;
    protected $table = 'detail_request_inventories';

    public function request_inventori()
    {
        return $this->belongsTo(RequestInventori::class, 'request_inventori_id', 'id');
    }

    public function inventori()
    {
        return $this->belongsTo(InventoriData::class, 'inventori_id', 'id');
    }
}
