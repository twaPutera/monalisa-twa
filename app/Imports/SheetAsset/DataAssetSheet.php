<?php

namespace App\Imports\SheetAsset;

use DateTime;
use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Vendor;
use App\Models\AssetData;
use App\Models\KelasAsset;
use App\Helpers\SsoHelpers;
use App\Models\SatuanAsset;
use App\Models\SistemConfig;
use App\Models\KategoriAsset;
use App\Helpers\QrCodeHelpers;
use App\Helpers\DepresiasiHelpers;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataAssetSheet implements ToModel, WithStartRow, WithValidation
{
    use Importable;
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        //dd($row);
        $get_kelas = KelasAsset::where('no_akun', $row[0])->first();
        if ($get_kelas == null) {
            $id_kelas = null;
        } else {
            $id_kelas = $get_kelas->id;
        }

        $get_vendor = Vendor::where('kode_vendor', $row[11])->first();
        if ($get_vendor == null) {
            $id_vendor = null;
        } else {
            $id_vendor = $get_vendor->id;
        }
        $id_kategori = KategoriAsset::query()->with(['group_kategori_asset'])->where('kode_kategori', $row[12])->first();
        $id_satuan = SatuanAsset::where('kode_satuan', $row[13])->first();
        $get_lokasi = Lokasi::where('kode_lokasi', $row[14])->first();

        if ($get_lokasi == null) {
            $id_lokasi = null;
        } else {
            $id_lokasi = $get_lokasi->id;
        }

        $user = SsoHelpers::getUserLogin();
        $tanggal_perolehan = Carbon::createFromFormat('d/m/Y', $row[4])->format('Y-m-d');
        $tgl_pelunasan = Carbon::createFromFormat('d/m/Y', $row[6])->format('Y-m-d');

        // Depresiasi
        $nilai_perolehan = $row[5];
        $nilai_perolehan = preg_replace('/[^0-9]/', '', $nilai_perolehan);
        $nilai_perolehan = intval($nilai_perolehan);

        $tgl_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($tanggal_perolehan);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($nilai_perolehan, ($id_kategori->umur_asset * 12));
        $umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($tanggal_perolehan, ($id_kategori->umur_asset * 12));

        if ($row[2] == null) {
            $row[2] = self::getMaxValueNoUrutAssetByKelompokId($id_kategori->id);
        }

        if ($row[1] == null) {
            $row[1] = self::generateKodeAsset($id_kategori->kode_kategori, $row[2]);
        }

        $qr_name = 'qr-asset-' . $row[1] . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($row[1], $path);

        $data_asset = AssetData::create([
            'id_vendor' => $id_vendor,
            'id_lokasi' => $id_lokasi,
            'id_kelas_asset' => $id_kelas,
            'id_kategori_asset' => $id_kategori->id,
            'id_satuan_asset' => $id_satuan->id,
            'kode_asset' => $row[1],
            'no_urut' => $row[2],
            'deskripsi' => $row[3],
            'tanggal_perolehan' => $tanggal_perolehan,
            'tgl_pelunasan' => $tgl_pelunasan,
            'nilai_perolehan' => $nilai_perolehan,
            'jenis_penerimaan' => $row[7],
            // 'nilai_buku_asset' => $row[7],
            // 'no_memo_surat' => $row[8],
            'no_po' => $row[8],
            'no_sp3' => $row[9],
            'no_seri' => $row[10],
            'tgl_register' => date('Y-m-d'),
            'register_oleh' => config('app.sso_siska') ? $user->guid : $user->id,
            'spesifikasi' => $row[15],
            'cost_center' => $row[16],
            // 'call_center' => $row[17],
            'status_kondisi' => $row[17],
            'is_pinjam' => $row[18] == 'iya' ? 1 : 0,
            'is_sparepart' => $row[19]  == 'iya' ? 1 : 0,
            'nilai_depresiasi' => $nilai_depresiasi,
            'umur_manfaat_komersial' => $umur_manfaat_komersial,
            'created_by' => config('app.sso_siska') ? $user->guid : $user->id,
            'qr_code' => $qr_name,
            'tanggal_awal_depresiasi' => $tgl_awal_depresiasi,
            'tanggal_akhir_depresiasi' => DepresiasiHelpers::getAkhirTanggalDepresiasi($tgl_awal_depresiasi, $id_kategori->umur_asset),
            'is_draft' => 1,
            'is_it' => $row[20] == 'IT' ? 1 : 0,
        ]);
        return $data_asset;
    }

    public function prepareForValidation($data, $index)
    {
        if (isset($data[4]) && is_numeric($data[4])) {
            $dateValue = intval($data[4]);
            $date = DateTime::createFromFormat('Y-m-d', '1900-01-01')->modify('+' . ($dateValue - 2) . ' days');
            if ($date !== false) {
                $data[4] = $date->format('d/m/Y');
            }
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            '0' => 'nullable|max:255',
            '1' => 'nullable|unique:asset_data,kode_asset|max:255',
            '2' => 'nullable',
            '3' => 'required|max:255',
            '4' => 'required|date_format:d/m/Y',
            '5' => 'required',
            '6' => 'erquired|date_format:d/m/Y',
            '7' => 'required|in:PO,Hibah Eksternal,Hibah Penelitian,Hibah Perorangan,UMK,CC,Reimburse',
            // '7' => 'required|numeric',
            // '8' => 'nullable|max:50',
            '8' => 'nullable|max:50',
            '9' => 'nullable|max:50',
            '10' => 'nullable|max:50',
            // '11' => 'required|numeric',
            '11' => 'nullable|exists:vendors,kode_vendor',
            '12' => 'required|exists:kategori_assets,kode_kategori',
            '13' => 'required|exists:satuan_assets,kode_satuan',
            '14' => 'nullable|exists:lokasis,kode_lokasi',
            '15' => 'required|max:255',
            '16' => 'nullable|max:255',
            // '17' => 'nullable|max:50',
            // '18' => 'nullable|numeric',
            // '19' => 'nullable|numeric',
            '17' => 'required|in:bagus,rusak,maintenance,tidak-lengkap,pengembangan',
            '18' => 'required|in:iya,tidak',
            '19' => 'required|in:iya,tidak',
            '20' => 'required|in:IT,Asset',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Nomor Akun Asset',
            '1' => 'Kode Asset',
            '2' => 'No Urut Asset',
            '3' => 'Deskripsi Asset',
            '4' => 'Tanggal Perolehan',
            '5' => 'Nilai Perolehan',
            '6' => 'Tanggal Pelunasan',
            '7' => 'Jenis Perolehan',
            // '7' => 'Nilai Buku Asset',
            // '8' => 'No Memorandum',
            '8' => 'No PO',
            '9' => 'No SP3',
            '10' => 'No Seri Asset',
            // '11' => 'Nilai Depresiasi',
            '11' => 'Kode Vendor Asset',
            '12' => 'Kode Jenis Asset',
            '13' => 'Kode Satuan Asset',
            '14' => 'Kode Lokasi Asset',
            '15' => 'Spesifikasi Asset',
            '16' => 'Cost Center/Asset Holder',
            // '17' => 'Call Center',
            // '18' => 'Umur Manfaat Fisikal',
            // '19' => 'Umur Manfaat Komersial',
            '17' => 'Status Kondisi Asset',
            '18' => 'Status Peminjaman',
            '19' => 'Status Sparepart',
            '20' => 'Status Pemilik Barang',
        ];
    }

    private static function generateKodeAsset($kode_kategori, $satuan)
    {
        $kode_asset = $kode_kategori . $satuan;
        $kode_asset = str_replace(' ', '', $kode_asset);
        return $kode_asset;
    }

    private static function getMaxValueNoUrutAssetByKelompokId(string $id)
    {
        $asset = AssetData::query()
            ->where('id_kategori_asset', $id)
            ->whereRaw('no_urut REGEXP "^([,|.]?[0-9])+$"')
            ->max('no_urut');

        $no_urut_config = SistemConfig::query()
            ->where('config', 'min_no_urut')
            ->first();

        $config = $no_urut_config->value ?? 5;

        $no = 1;

        logger('asset', [$asset]);

        if (isset($asset)) {
            $no = $asset + 1;
        }

        $no_urut = str_pad($no, $config, '0', STR_PAD_LEFT);

        logger('no_urut', [$no_urut]);

        return $no_urut;
    }
}
