<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriInventori extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'kategori_inventories';

    public function inventori_data()
    {
        return $this->hasMany(InventoriData::class, 'id_kategori_inventori', 'id');
    }
}
