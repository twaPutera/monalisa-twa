<?php

namespace App\Helpers;

class StatusAssetDataHelpers
{
    public static function listStatusAssetData()
    {
        return [
            'DB' => 'Digunakan - Kondisi Bagus',
            'DS' => 'Digunakan - Kondisi Sedang',
            'DJ' => 'Digunakan - Kondisi Jelek',
            'DL' => 'Digunakan - Kondisi Tidak Lengkap',
            'TR' => 'Tidak Digunakan - Kondisi Rusak',
            'TS' => 'Tidak Digunakan - Kondisi Salvage',
            'TT' => 'Tidak Digunakan - Tunggu Pemasangan',
            'TX' => 'Tidak Digunakan - Tidak Ditemukan',
            'TC' => 'Tidak Digunakan - Cadangan',
            'TL' => 'Tidak Digunakan - Berlebih',
            'TP' => 'Tidak Digunakan - Dalam Perbaikan',
            'FB' => 'FUPP - Eks Kondisi Bagus',
            'FC' => 'FUPP - Eks Cadangan',
            'FJ' => 'FUPP - Eks Kondisi Jelek',
            'FL' => 'FUPP - Eks Berlebih',
            'FP' => 'FUPP - Eks Kondisi Perbaikan',
            'FR' => 'FUPP - Eks Kondisi Rusak',
            'FS' => 'FUPP - Eks Kondisi Sedang',
            'FT' => 'FUPP - Eks Tunggu Pesang/Dokumen',
            'FX' => 'FUPP - Eks Tidak Ditemukan',
            'MJ' => 'Menunggu Dijual',
        ];
    }

    public static function getStatusAssetData($key)
    {
        $list = self::listStatusAssetData();

        return $list[$key];
    }
}
