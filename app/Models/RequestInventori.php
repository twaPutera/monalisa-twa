<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestInventori extends Model
{
    use HasFactory, Uuid;
    protected $table = 'request_inventories';

    public function detail_request_inventori()
    {
        return $this->hasMany(DetailRequestInventori::class, 'request_inventori_id', 'id');
    }

    public function log_request_inventori()
    {
        return $this->hasMany(LogRequestInventori::class, 'request_inventori_id', 'id');
    }

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }
}
