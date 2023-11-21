<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemindahanAsset extends Model
{
    use HasFactory, Uuid;

    public function detail_pemindahan_asset()
    {
        return $this->hasOne(DetailPemindahanAsset::class, 'id_pemindahan_asset', 'id');
    }

    public function approval_pemindahan_asset()
    {
        return $this->hasOne(ApprovalPemindahanAsset::class, 'id_pemindahan_asset', 'id');
    }

    public function approval()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
