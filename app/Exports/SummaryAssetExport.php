<?php

namespace App\Exports;

use App\Models\AssetData;
use App\Helpers\SsoHelpers;
use Illuminate\Support\Facades\DB;
use App\Services\User\UserQueryServices;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Services\UserSso\UserSsoQueryServices;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummaryAssetExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    protected $id_lokasi;
    protected $id_kategori_asset;
    protected $number;
    protected $userSsoQueryServices;
    protected $userQueryServices;
    public function __construct($lokasi = null, $kategori_asset = null)
    {
        $this->id_lokasi = $lokasi;
        $this->id_kategori_asset = $kategori_asset;
        $this->number = 0;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function query()
    {
        $query = AssetData::query();
        // ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image', 'log_asset_opname', 'detail_peminjaman_asset', 'detail_pemindahan_asset']);
        $query->leftJoin('satuan_assets', 'satuan_assets.id', '=', 'asset_data.id_satuan_asset');
        $query->leftJoin('vendors', 'vendors.id', '=', 'asset_data.id_vendor');
        $query->leftJoin('lokasis', 'lokasis.id', '=', 'asset_data.id_lokasi');
        $query->leftJoin('kelas_assets', 'kelas_assets.id', '=', 'asset_data.id_kelas_asset');
        $query->leftJoin('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset');
        $query->leftJoin('detail_peminjaman_assets', 'asset_data.id', '=', 'detail_peminjaman_assets.id_asset');
        $query->leftJoin('detail_pemindahan_assets', 'asset_data.id', '=', 'detail_pemindahan_assets.id_asset');
        $query->leftJoin('log_asset_opnames', function ($join) {
            $join->on('asset_data.id', '=', 'log_asset_opnames.id_asset_data')
                ->where('log_asset_opnames.created_at', '=', DB::raw('(select max(`created_at`) from log_asset_opnames)'));
        });
        $query->leftJoin('peminjaman_assets', function ($join) {
            $join->on('peminjaman_assets.id', '=', 'detail_peminjaman_assets.id_peminjaman_asset')
                ->where('peminjaman_assets.created_at', '=', DB::raw('(select max(`created_at`) from peminjaman_assets)'));
        });
        $query->leftJoin('pemindahan_assets', function ($join) {
            $join->on('pemindahan_assets.id', '=', 'detail_pemindahan_assets.id_pemindahan_asset')
                ->where('pemindahan_assets.created_at', '=', DB::raw('(select max(`created_at`) from pemindahan_assets)'));
        });
        $query->select([
            'asset_data.kode_asset',
            'asset_data.deskripsi',
            'asset_data.is_inventaris',
            'kelas_assets.nama_kelas',
            'kategori_assets.nama_kategori',
            'asset_data.status_kondisi',
            'asset_data.status_akunting',
            'asset_data.tanggal_perolehan',
            'asset_data.tgl_pelunasan',
            'asset_data.nilai_perolehan',
            'lokasis.nama_lokasi',
            'asset_data.ownership',
            'asset_data.register_oleh',
            'satuan_assets.nama_satuan',
            'vendors.nama_vendor',
            'log_asset_opnames.kode_opname',
            'log_asset_opnames.tanggal_opname',
            'log_asset_opnames.keterangan',
            'log_asset_opnames.created_by as opname_by',
            'peminjaman_assets.tanggal_peminjaman',
            'peminjaman_assets.tanggal_pengembalian',
            'peminjaman_assets.status as status_peminjaman',
            'peminjaman_assets.json_peminjam_asset',
            'pemindahan_assets.tanggal_pemindahan',
            'pemindahan_assets.json_penyerah_asset',
            'pemindahan_assets.json_penerima_asset',
        ]);
        if (isset($this->id_lokasi) && $this->id_lokasi != 'root') {
            $query->where('asset_data.id_lokasi', $this->id_lokasi);
        }

        if (isset($this->id_kategori_asset)) {
            $query->where('asset_data.id_kategori_asset', $this->id_kategori_asset);
        }
        $query->where('asset_data.is_pemutihan', 0);
        // $user = SsoHelpers::getUserLogin();
        // if ($user) {
        //     if ($user->role == 'manager_it' || $user->role == "staff_it") {
        //         $query->where('is_it', 1);
        //     } else if ($user->role == 'manager_asset' || $user->role == "staff_asset") {
        //         $query->where('is_it', 0);
        //     }
        // }
        $query->where('asset_data.is_draft', 0);
        // dd($query->get());
        return $query;
    }

    public function title(): string
    {
        return 'Summary Asset';
    }

    public function map($item): array
    {
        $tipe = $item->is_inventaris == 1 ? 'Inventaris' : 'Asset';
        $ownership = '-';
        $register_oleh = '-';
        $opname_by = '-';
        if (config('app.sso_siska')) {
            $find_ownership = $item->ownership == null ? null : $this->userSsoQueryServices->getUserByGuid($item->ownership);
            $ownership = isset($find_ownership[0]) ? $find_ownership[0]['nama'] : 'Not Found';

            $find_register = $item->register_oleh == null ? null : $this->userSsoQueryServices->getUserByGuid($item->register_oleh);
            $register_oleh = isset($find_register[0]) ? $find_register[0]['nama'] : 'Not Found';

            $find_opname = $this->userSsoQueryServices->getUserByGuid($item->opname_by);
            $opname_by = isset($find_opname[0]) ? $find_opname[0]['nama'] : 'Not Found';
        } else {
            $find_ownership = $item->ownership == null ? null : $this->userQueryServices->findById($item->ownership);
            $ownership = isset($find_ownership) ? $find_ownership->name : 'Not Found';

            $find_register = $item->register_oleh == null ? null : $this->userQueryServices->findById($item->register_oleh);
            $register_oleh = isset($find_register) ? $find_register->name : 'Not Found';

            $find_opname = $this->userQueryServices->findById($item->opname_by);
            $opname_by = isset($find_opname) ? $find_opname->name : 'Not Found';
        }
        $peminjam = $item->json_peminjam_asset ? json_decode($item->json_peminjam_asset) : 'Not Found';
        $peminjam_name = $peminjam->name ?? 'Not Found';

        $penyerah = $item->json_penyerah_asset ? json_decode($item->json_penyerah_asset) : 'Not Found';
        $penyerah_name = $penyerah->nama ?? 'Not Found';

        $penerima = $item->json_penerima_asset ? json_decode($item->json_penerima_asset) : 'Not Found';
        $penerima_name = $penerima->nama ?? 'Not Found';
        return [
            $this->number += 1,
            $item->kode_asset,
            $item->deskripsi,
            $tipe,
            $item->nama_kelas,
            $item->nama_kategori,
            $item->status_kondisi,
            $item->status_akunting,
            $item->tanggal_perolehan,
            $item->nilai_perolehan,
            $item->tgl_pelunasan,
            $item->nama_lokasi,
            $ownership,
            $register_oleh,
            $item->nama_satuan,
            $item->nama_vendor,
            $item->tanggal_opname,
            $item->kode_opname,
            $item->keterangan,
            $opname_by,
            $item->tanggal_peminjaman,
            $item->tanggal_pengembalian,
            $item->status_peminjaman,
            $peminjam_name,
            $item->tanggal_pemindahan,
            $penyerah_name,
            $penerima_name,
        ];
    }

    public function headings(): array
    {
        return ['No', 'Kode Asset', 'Deskripsi Asset', 'Tipe Asset', 'Asset Group', 'Jenis Asset', 'Status Kondisi', 'Status Akunting', 'Tgl Perolehan', 'Nilai Perolehan', 'Tgl Pelunasan', 'Lokasi', 'Ownership', 'Register Oleh', 'Satuan', 'Vendor', 'Tgl Opname Terakhir', 'Kode Opname', 'Catatan Opname Terakhir', 'User Opname Terakhir', 'Tgl Peminjaman Terakhir', 'Tgl Pengembalian Peminjaman Terakhir', 'Status Peminjaman Terakhir', 'User Peminjaman Terakhir', 'Tgl Pemindahan Asset Terakhir', 'User Penyerah Asset', 'User Penerima Asset'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];
    }
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $lastCell = $highestColumn . $highestRow;
                $rangeCell = 'A1:' . $lastCell;
                $event->sheet->getDelegate()->getStyle($rangeCell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
