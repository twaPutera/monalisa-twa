<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalPemutihanAsset extends Model
{
    use HasFactory, Uuid;

    public function pemutihan_asset()
    {
        return $this->belongsTo(PemutihanAsset::class, 'id_pemutihan_asset', 'id');
    }
}
