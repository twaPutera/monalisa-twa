<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Helpers\DateIndoHelpers;
use App\Models\RequestInventori;
use App\Models\DetailRequestInventori;
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

class RequestBahanHabisPakaiExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    protected $awal_permintaan;
    protected $akhir_permintaan;
    protected $awal_pengambilan;
    protected $akhir_pengambilan;
    protected $status_permintaan;
    protected $number;
    protected $array_merge_cell;
    protected $temp_row_num;
    protected $userSsoQueryServices;
    protected $userQueryServices;

    public function __construct(
        $awal_permintaan = null,
        $akhir_permintaan = null,
        $awal_pengambilan = null,
        $akhir_pengambilan = null,
        $status_permintaan = null
    ) {
        $this->awal_permintaan = $awal_permintaan;
        $this->akhir_permintaan = $akhir_permintaan;
        $this->awal_pengambilan = $awal_pengambilan;
        $this->akhir_pengambilan = $akhir_pengambilan;
        $this->status_permintaan = $status_permintaan;
        $this->number = 0;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
        $this->array_merge_cell = [
            'no' => [],
            'code' => [],
            'bahan_habis_pakai' => [],
        ];
        $this->temp_row_num = 1;
    }

    public function query()
    {
        $query = RequestInventori::query()
            ->with(['log_request_inventori'])
            ->has('log_request_inventori');

        if (isset($this->awal_permintaan)) {
            $query->where('request_inventories.created_at', '>=', $this->awal_permintaan . ' 00:00:00');
        }

        if (isset($this->akhir_permintaan)) {
            $query->where('request_inventories.created_at', '<=', $this->akhir_permintaan . ' 23:59:00');
        }

        if (isset($this->awal_pengambilan)) {
            $query->where('tanggal_pengambilan', '>=', $this->awal_pengambilan);
        }

        if (isset($this->akhir_pengambilan)) {
            $query->where('tanggal_pengambilan', '<=', $this->akhir_pengambilan);
        }

        if (isset($this->status_permintaan)) {
            if ($this->status_permintaan != 'all') {
                $query->whereHas('log_request_inventori', function ($subQuery) {
                    $subQuery->where('status', $this->status_permintaan);
                });
            }
        }

        return $query;
    }

    public function title(): string
    {
        return 'History Permintaan Bahan Habis Pakai';
    }

    public function map($item): array
    {
        $this->temp_row_num++;
        $data = [];
        $t = [
            'start' => $this->temp_row_num,
            'end' => null,
        ];
        foreach ($item->log_request_inventori as $key => $log) {
            $name = '-';
            if (config('app.sso_siska')) {
                $user = $log->request_inventori->guid_pengaju == null ? null : $this->userSsoQueryServices->getUserByGuid($log->request_inventori->guid_pengaju);
                $name = isset($user[0]) ? collect($user[0]) : null;
            } else {
                $user = $this->userQueryServices->findById($log->request_inventori->guid_pengaju);
                $name = isset($user) ? $user->name : 'Not Found';
            }

            $find_detail_asset = DetailRequestInventori::with(['inventori'])->where('request_inventori_id', $log->request_inventori_id)->get();
            $element = '';
            foreach ($find_detail_asset as $index => $item_inventori) {
                if ($index >= 1) {
                    $element .= ', ';
                }
                $element .= $item_inventori->inventori->kode_inventori . ' (' . $item_inventori->inventori->deskripsi_inventori . ')';
            }

            if ($key == 0) {
                $data[] = [
                    $this->number += 1,
                    $log->request_inventori->kode_request,
                    $element,
                    DateIndoHelpers::formatDateToIndo(Carbon::parse($log->tanggal_permintaan)->format('Y-m-d')),
                    DateIndoHelpers::formatDateToIndo(Carbon::parse($log->created_at)->format('Y-m-d')),
                    DateIndoHelpers::formatDateToIndo($log->request_inventori->tanggal_pengambilan),
                    $name,
                    $log->request_inventori->no_memo,
                    $log->request_inventori->unit_kerja,
                    $log->request_inventori->jabatan,
                    $log->request_inventori->alasan,
                    $log->message,
                    $log->status,
                    $log->created_by,
                ];
            } else {
                $data[] = [
                    '',
                    '',
                    '',
                    DateIndoHelpers::formatDateToIndo(Carbon::parse($log->tanggal_permintaan)->format('Y-m-d')),
                    DateIndoHelpers::formatDateToIndo(Carbon::parse($log->created_at)->format('Y-m-d')),
                    DateIndoHelpers::formatDateToIndo($log->request_inventori->tanggal_pengambilan),
                    $name,
                    $log->request_inventori->no_memo,
                    $log->request_inventori->unit_kerja,
                    $log->request_inventori->jabatan,
                    $log->request_inventori->alasan,
                    $log->message,
                    $log->status,
                    $log->created_by,
                ];
                $this->temp_row_num++;
                $end = $this->temp_row_num;
                $t['end'] = $end;
            }
        }

        $this->array_merge_cell['no'][] = $t;
        $this->array_merge_cell['code'][] = $t;
        $this->array_merge_cell['bahan_habis_pakai'][] = $t;

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Permintaan',
            'Jenis Bahan Habis Pakai Dalam Permintaan Ini',
            'Tanggal Permintaan',
            'Log Terakhir',
            'Tanggal Pengambilan',
            'User Pengaju',
            'No Memo',
            'Unit Kerja',
            'Jabatan',
            'Alasan Permintaan',
            'Aktifitas',
            'Status',
            'Dilakukan Oleh',
        ];
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
                //set heading bold and center
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                foreach ($this->array_merge_cell['no'] as $key => $value) {
                    $event->sheet->mergeCells('A' . $value['start'] . ':A' . $value['end']);
                    $event->sheet->getStyle('A' . $value['start'] . ':A' . $value['end'])->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }

                foreach ($this->array_merge_cell['code'] as $key => $value) {
                    $event->sheet->mergeCells('B' . $value['start'] . ':B' . $value['end']);
                    $event->sheet->getStyle('B' . $value['start'] . ':B' . $value['end'])->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }

                foreach ($this->array_merge_cell['bahan_habis_pakai'] as $key => $value) {
                    $event->sheet->mergeCells('C' . $value['start'] . ':C' . $value['end']);
                    $event->sheet->getStyle('C' . $value['start'] . ':C' . $value['end'])->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }

                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $lastCell = $highestColumn . $highestRow;
                $rangeCell = 'A1:' . $lastCell;

                $event->sheet->getDelegate()->getStyle($rangeCell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
