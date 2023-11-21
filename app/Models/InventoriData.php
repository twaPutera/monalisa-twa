<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoriData extends Model
{
    use HasFactory, Uuid;

    public function kategori_inventori()
    {
        return $this->belongsTo(KategoriInventori::class, 'id_kategori_inventori', 'id');
    }

    public function detail_request_inventori()
    {
        return $this->hasMany(DetailRequestInventori::class, 'inventori_id', 'id');
    }

    public function satuan_inventori()
    {
        return $this->belongsTo(SatuanInventori::class, 'id_satuan_inventori', 'id');
    }

    public function log_penambahan_inventori()
    {
        return $this->hasMany(LogPenambahanInventori::class, 'id_inventori', 'id');
    }

    public function log_pengurangan_inventori()
    {
        return $this->hasMany(LogPenguranganInventori::class, 'id_inventori', 'id');
    }
}
