<?php

namespace App\Helpers;

use App\Models\AssetData;
use App\Models\DepresiasiAsset;

class DepresiasiHelpers
{
    public static function getNilaiDepresiasi($nilai_perolehan, $lama_depresiasi)
    {
        $nilai_depresiasi = 0;
        if ($lama_depresiasi > 0) {
            $nilai_depresiasi = $nilai_perolehan / $lama_depresiasi;
        }
        return $nilai_depresiasi;
    }

    public static function sumAllDepresiasiAsset($id_asset, $year = null)
    {
        $query = DepresiasiAsset::query()
            ->where('id_asset_data', $id_asset);

        if (isset($year)) {
            $query->whereYear('tanggal_depresiasi', $year);
        }

        $sum = $query->sum('nilai_depresiasi');

        return $sum;
    }

    public static function getSisaBulanDepresiasi($tanggal_akhir, $id_asset)
    {
        $depresiasi = DepresiasiAsset::query()
            ->where('id_asset_data', $id_asset)
            ->orderby('tanggal_depresiasi', 'desc')
            ->first();

        $diff_month = self::getDiffOfMonth($tanggal_akhir, $depresiasi->tanggal_depresiasi ?? $tanggal_akhir);
        return $diff_month > 0 ? $diff_month : 0;
    }

    public static function getNilaiBukuAwalTahun($id_asset, $year)
    {
        $depresiasi = DepresiasiAsset::query()
            ->where('id_asset_data', $id_asset)
            ->whereYear('tanggal_depresiasi', $year)
            ->whereMonth('tanggal_depresiasi', 1)
            ->first();

        return isset($depresiasi) ? $depresiasi->nilai_buku_akhir : 0;
    }

    public static function getNilaiBukuAkhirTahun($id_asset, $year)
    {
        $depresiasi = DepresiasiAsset::query()
            ->where('id_asset_data', $id_asset)
            ->whereYear('tanggal_depresiasi', $year)
            ->whereMonth('tanggal_depresiasi', 12)
            ->first();

        return isset($depresiasi) ? $depresiasi->nilai_buku_akhir : 0;
    }

    public static function getDataAssetDepresiasi($date)
    {
        $asset = AssetData::query()
            ->select([
                'id',
                'nilai_buku_asset',
                'nilai_depresiasi',
                'nilai_perolehan',
                'tgl_pelunasan',  //ini ditambahkan oleh wahyu ap
            ])
            ->where('is_pemutihan', '0')
            // ->where('is_draft', '0')
            ->where('is_inventaris', '0')
            ->where('nilai_buku_asset', '>', 1)
            ->where('nilai_depresiasi', '>', 0)
            ->whereNotIn('status_kondisi', ['draft', 'pengembangan'])
            ->whereDoesntHave('depresiasi_asset', function ($query) use ($date) {
                $query->whereMonth('tanggal_depresiasi', date('m', strtotime($date)))
                    ->whereYear('tanggal_depresiasi', date('Y', strtotime($date)));
            })
            ->get();
        return $asset;
    }

    public static function depresiasiAsset(AssetData $asset, $tanggal_depresiasi)
    {
        $nilai_buku_akhir = $asset->nilai_buku_asset - $asset->nilai_depresiasi;

        $depresiasi = new DepresiasiAsset();
        $depresiasi->id_asset_data = $asset->id;
        $depresiasi->nilai_depresiasi = $asset->nilai_depresiasi;
        $depresiasi->tanggal_depresiasi = $tanggal_depresiasi;
        $depresiasi->nilai_buku_awal = $asset->nilai_buku_asset;
        $depresiasi->nilai_buku_akhir = $nilai_buku_akhir < 1 ? 1 : $nilai_buku_akhir;
        $depresiasi->save();

        $asset->nilai_buku_asset = $nilai_buku_akhir < 1 ? 1 : $nilai_buku_akhir;
        $asset->save();

        return [$depresiasi->id, $asset->id, $asset->nilai_buku_asset, $nilai_buku_akhir];
    }

    public static function getAwalTanggalDepresiasi($date)
    {
        if (date('d', strtotime($date)) > 15) {
            $date = date('Y-m-d', strtotime($date . ' +1 month'));
        }
        return date('Y-m-15', strtotime($date));
    }

    public static function getAkhirtanggalDepresiasi($date, $tahun)
    {
        $date = date('Y-m-15', strtotime($date . $tahun . ' year'));
        return $date;
    }

    public static function getDiffOfMonth($date1, $date2)
    {
        $date1 = new \DateTime($date1);
        $date2 = new \DateTime($date2);
        $diff = $date1->diff($date2);
        return $diff->m + ($diff->y * 12);
    }

    public static function generateUmurAsset($date, $umur)
    {
        $diff = self::getDiffOfMonth($date, date('Y-m-d'));
        $umur_asset = $umur - $diff;

        return ($umur_asset / 12);
    }

    public static function storePastDepresiasiAsset(AssetData $asset, $tanggal_awal_depresiasi)
    {
        $nilai_buku = $asset->nilai_perolehan;
        $lama_bulan = self::getDiffOfMonth($tanggal_awal_depresiasi, date('Y-m-d'));

        if (date('d') < 15) {
            $lama_bulan = $lama_bulan - 1;
        }

        for ($i=0; $i<$lama_bulan; $i++) {
            $tanggal_loop = date('Y-m-d', strtotime($tanggal_awal_depresiasi . ' +'.$i.' month'));
            $nilai_buku_awal = $nilai_buku;
            $nilai_buku = $nilai_buku - $asset->nilai_depresiasi;

            $depresiasi = new DepresiasiAsset();
            $depresiasi->id_asset_data = $asset->id;
            $depresiasi->nilai_depresiasi = $asset->nilai_depresiasi;
            $depresiasi->tanggal_depresiasi = $tanggal_loop;
            $depresiasi->nilai_buku_awal = $nilai_buku_awal;
            $depresiasi->nilai_buku_akhir = $nilai_buku < 1 ? 1 : $nilai_buku;
            $depresiasi->save();
        }

        return $nilai_buku;
    }
}
