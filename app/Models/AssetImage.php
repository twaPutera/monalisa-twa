<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetImage extends Model
{
    use HasFactory, Uuid;

    public function imageable()
    {
        return $this->morphTo();
    }
}
