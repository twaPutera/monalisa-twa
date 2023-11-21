<?php

namespace App\Exports;

use App\Helpers\SsoHelpers;
use App\Models\LogPengaduanAsset;
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

class PengaduanExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    protected $awal;
    protected $akhir;
    protected $id_lokasi;
    protected $number;
    protected $id_kategori_asset;
    protected $id_asset_data;
    protected $status_pengaduan;
    protected $userSsoQueryServices;
    protected $userQueryServices;

    public function __construct(
        $tgl_awal = null,
        $tgl_akhir = null,
        $lokasi = null,
        $kategori_asset = null,
        $asset_data = null,
        $status_pengaduan = null
    ) {
        $this->awal = $tgl_awal;
        $this->akhir = $tgl_akhir;
        $this->id_lokasi = $lokasi;
        $this->number = 0;
        $this->id_kategori_asset = $kategori_asset;
        $this->id_asset_data = $asset_data;
        $this->status_pengaduan = $status_pengaduan;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function query()
    {
        $query = LogPengaduanAsset::query();
        $query->join('pengaduans', 'log_pengaduan_assets.id_pengaduan', '=', 'pengaduans.id');
        $query->leftJoin('lokasis', 'pengaduans.id_lokasi', '=', 'lokasis.id');
        $query->leftJoin('asset_data', 'pengaduans.id_asset_data', '=', 'asset_data.id');
        $query->leftJoin('kategori_assets', 'asset_data.id_kategori_asset', '=', 'kategori_assets.id');
        $query->leftJoin('group_kategori_assets', 'kategori_assets.id_group_kategori_asset', '=', 'group_kategori_assets.id');
        $query->select([
            'pengaduans.tanggal_pengaduan',
            'pengaduans.kode_pengaduan',
            'pengaduans.prioritas',
            'asset_data.deskripsi',
            'group_kategori_assets.nama_group',
            'kategori_assets.nama_kategori',
            'lokasis.nama_lokasi',
            'pengaduans.created_by',
            'pengaduans.catatan_pengaduan',
            'pengaduans.catatan_admin',
            'log_pengaduan_assets.status',
            'log_pengaduan_assets.created_at as log_terakhir',
            'log_pengaduan_assets.message_log',
            'log_pengaduan_assets.created_by as dilakukan_oleh',
        ]);

        $user = SsoHelpers::getUserLogin();
        if ($user) {
            if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                $query->where('asset_data.is_it', 1);
            } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                $query->orWhere('pengaduans.id_asset_data', null);
                $query->orWhere('asset_data.is_it', 0);
            }
        }

        if (isset($this->id_lokasi) && $this->id_lokasi != 'root') {
            $query->where('pengaduans.id_lokasi', $this->id_lokasi);
        }
        if (isset($this->id_kategori_asset)) {
            $query->where('asset_data.id_kategori_asset', $this->id_kategori_asset);
        }

        if (isset($this->awal)) {
            $query->where('pengaduans.tanggal_pengaduan', '>=', $this->awal);
        }

        if (isset($this->akhir)) {
            $query->where('pengaduans.tanggal_pengaduan', '<=', $this->akhir);
        }

        if (isset($this->id_asset_data)) {
            $query->where('pengaduans.id_asset_data', $this->id_asset_data);
        }

        if (isset($this->status_pengaduan)) {
            if ($this->status_pengaduan != 'all') {
                $query->where('log_pengaduan_assets.status', $this->status_pengaduan);
            }
        }

        $query->orderBy('log_pengaduan_assets.created_at', 'DESC');
        return $query;
    }

    public function title(): string
    {
        return 'History Pengaduan';
    }

    public function map($item): array
    {
        $name = '-';
        if (config('app.sso_siska')) {
            $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
            $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
        } else {
            $user = $item->created_by == null ? null : $this->userQueryServices->findById($item->created_by);
            $name = isset($user) ? $user->name : 'Not Found';
        }

        $dilakukan_oleh = '-';
        if (config('app.sso_siska')) {
            $user_melakukan = $item->dilakukan_oleh == null ? null : $this->userSsoQueryServices->getUserByGuid($item->dilakukan_oleh);
            $dilakukan_oleh = isset($user_melakukan[0]) ? $user_melakukan[0]['nama'] : 'Not Found';
        } else {
            $user_melakukan = $item->dilakukan_oleh == null ? null : $this->userQueryServices->findById($item->dilakukan_oleh);
            $dilakukan_oleh = isset($user_melakukan) ? $user_melakukan->name : 'Not Found';
        }

        if ($item->prioritas == 10) {
            $prioritas = 'High';
        } elseif ($item->prioritas == 5) {
            $prioritas = 'Medium';
        } elseif ($item->prioritas == 1) {
            $prioritas = 'Low';
        } else {
            $prioritas = 'Tidak Ada';
        }

        return [
            $this->number += 1,
            $item->tanggal_pengaduan,
            $item->kode_pengaduan,
            $item->deskripsi ?? '-',
            $item->nama_group ?? '-',
            $item->nama_kategori ?? '-',
            $item->nama_lokasi ?? '-',
            $name,
            $prioritas,
            $item->catatan_pengaduan,
            $item->catatan_admin,
            $item->status == 'dilaporkan' ? 'laporan masuk' : $item->status,
            $item->log_terakhir,
            $item->message_log,
            $dilakukan_oleh,
        ];
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Pengaduan Masuk', 'Kode Pengaduan', 'Nama Asset Yang Diadukan', 'Kelompok Asset', 'Jenis Asset', 'Nama Lokasi Yang Diadukan', 'Dilaporkan Oleh', 'Prioritas', 'Catatan Pengaduan', 'Catatan Admin', 'Status Pengaduan', 'Log Terakhir', 'Aktifitas', 'Dilakukan Oleh'];
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
