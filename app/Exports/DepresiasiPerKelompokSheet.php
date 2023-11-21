<?php

namespace App\Exports;

use App\Models\AssetData;
use App\Helpers\DepresiasiHelpers;
use App\Models\GroupKategoriAsset;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DepresiasiPerKelompokSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStyles
{
    private $year;
    private $group;

    public function __construct($year, GroupKategoriAsset $group)
    {
        $this->year = $year;
        $this->group = $group;
    }

    public function sheets(): array
    {
        $sheets = [];

        return $sheets;
    }

    public function collection()
    {
        $i = 1;
        $asset = AssetData::query()
            ->join('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset')
            ->join('group_kategori_assets', 'group_kategori_assets.id', '=', 'kategori_assets.id_group_kategori_asset')
            ->join('lokasis', 'lokasis.id', '=', 'asset_data.id_lokasi')
            ->join('satuan_assets', 'satuan_assets.id', '=', 'asset_data.id_satuan_asset')
            ->join('kelas_assets', 'kelas_assets.id', '=', 'asset_data.id_kelas_asset')
            ->select([
                'asset_data.id',
                'kelas_assets.no_akun',
                'asset_data.kode_asset',
                'group_kategori_assets.nama_group',
                'kategori_assets.nama_kategori',
                'asset_data.deskripsi',
                'asset_data.tanggal_perolehan',
                'lokasis.nama_lokasi',
                'asset_data.nilai_perolehan',
                'satuan_assets.nama_satuan',
                'asset_data.status_akunting',
                'asset_data.umur_manfaat_komersial',
                'asset_data.spesifikasi',
                'asset_data.tanggal_awal_depresiasi',
                'asset_data.tanggal_akhir_depresiasi',
                'asset_data.nilai_depresiasi',
                'asset_data.nilai_buku_asset',
            ])
            ->where('group_kategori_assets.id', $this->group->id)
            ->where('is_inventaris', '0')
            ->where('is_pemutihan', '0')
            ->get()->map(function ($item) use (&$i) {
                $item->no = $i;
                $sisa_bulan = DepresiasiHelpers::getSisaBulanDepresiasi($item->tanggal_akhir_depresiasi, $item->id);
                $beban_penyusutan = $item->nilai_depresiasi * $sisa_bulan;
                $i++;
                $item->penyusutan_pertahun = $item->nilai_depresiasi * 12;
                $item->akm_penyusutan_awal_tahun = DepresiasiHelpers::sumAllDepresiasiAsset($item->id); //sum all depresiasi
                $item->nilai_buku_awal_tahun = DepresiasiHelpers::getNilaiBukuAwalTahun($item->id, $this->year); //sum all nilai buku
                $item->beban_penyusutan = $item->penyusutan_pertahun < $beban_penyusutan ? $item->penyusutan_pertahun : $beban_penyusutan; //sum all depresiasi
                $item->nilai_buku_akhir_tahun = DepresiasiHelpers::getNilaiBukuAkhirTahun($item->id, $this->year); //sum all nilai buku
                return $item;
            });

        return $asset;
    }

    public function title(): string
    {
        return $this->group->nama_group ?? 'Unknown';
    }

    public function headings(): array
    {
        return [
            ['No', 'Kode Akun', 'Kode Aset', 'Rincian Kode Aset', '', 'Deskripsi Aset', 'Tanggal Perolehan', 'Lokasi', 'Nilai Perolehan', 'Satuan', 'Status Akunting', 'Umur Manfaat Komersial', 'Spesifikasi', 'Mulai', '', 'Akhir', 'Bbn Penyusutan Per Tahun', 'Akm. Penyusutan Awal Tahun', 'Nilai Buku Awal Tahun', 'Beban Penyusutan', 'Nilai Buku Akhir Tahun', 'Nilai Buku Saat Ini'],
            ['', '', '', 'Kelompok Aset', 'Sub Kelompok Aset', '', '', '', '', '', '', '', '', 'Tahun', 'Bulan', 'Tahun', 'Bulan', '', '', '', '', '', ''],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            2 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];
    }

    public function map($item): array
    {
        return [
            $item->no,
            $item->no_akun,
            $item->kode_asset,
            $item->nama_group,
            $item->nama_kategori,
            $item->deskripsi,
            $item->tanggal_perolehan,
            $item->nama_lokasi,
            $item->nilai_perolehan,
            $item->nama_satuan,
            $item->status_akunting,
            $item->umur_manfaat_komersial,
            $item->spesifikasi,
            date('Y', strtotime($item->tanggal_awal_depresiasi)),
            date('m', strtotime($item->tanggal_awal_depresiasi)),
            date('Y', strtotime($item->tanggal_akhir_depresiasi)),
            date('m', strtotime($item->tanggal_akhir_depresiasi)),
            $item->penyusutan_pertahun,
            $item->akm_penyusutan_awal_tahun,
            $item->nilai_buku_awal_tahun,
            $item->beban_penyusutan,
            $item->nilai_buku_akhir_tahun,
            $item->nilai_buku_asset,
        ];
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:A2');
                $event->sheet->mergeCells('B1:B2');
                $event->sheet->mergeCells('C1:C2');
                $event->sheet->mergeCells('D1:E1');
                $event->sheet->mergeCells('F1:F2');
                $event->sheet->mergeCells('G1:G2');
                $event->sheet->mergeCells('H1:H2');
                $event->sheet->mergeCells('I1:I2');
                $event->sheet->mergeCells('J1:J2');
                $event->sheet->mergeCells('K1:K2');
                $event->sheet->mergeCells('L1:L2');
                $event->sheet->mergeCells('M1:M2');
                $event->sheet->mergeCells('N1:O1');
                $event->sheet->mergeCells('P1:Q1');
                $event->sheet->mergeCells('R1:R2');
                $event->sheet->mergeCells('S1:S2');
                $event->sheet->mergeCells('T1:T2');
                $event->sheet->mergeCells('U1:U2');
                $event->sheet->mergeCells('V1:V2');

                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $lastCell = $highestColumn . $highestRow;
                $rangeCell = 'A1:' . $lastCell;

                $event->sheet->getDelegate()->getStyle($rangeCell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
