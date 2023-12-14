<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit_kerja extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    public function asset_data()
    {
        return $this->hasMany(AssetData::class, 'id_unit_kerja', 'id');
    }
}
