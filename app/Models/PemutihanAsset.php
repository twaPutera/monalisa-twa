<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemutihanAsset extends Model
{
    use HasFactory, Uuid;

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    public function detail_pemutihan_asset()
    {
        return $this->hasMany(DetailPemutihanAsset::class, 'id_pemutihan_asset', 'id');
    }

    public function approval_pemutihan_asset()
    {
        return $this->hasMany(ApprovalPemutihanAsset::class, 'id_pemutihan_asset', 'id');
    }
}
