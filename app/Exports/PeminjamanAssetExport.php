<?php

namespace App\Exports;

use App\Models\PeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Services\User\UserQueryServices;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Services\UserSso\UserSsoQueryServices;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanAssetExport implements FromQuery, WithMapping, WithHeadings, WithEvents, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $start_date;
    protected $end_date;
    protected $array_merge_cell;
    protected $temp_row_num;
    protected $number;
    protected $userSsoQueryServices;
    protected $userQueryServices;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->number = 0;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
        $this->array_merge_cell = [
            'no' => [],
            'code' => [],
            'asset' => [],
        ];
        $this->temp_row_num = 1;
    }

    public function query()
    {
        $query = PeminjamanAsset::query()
            ->with(['log_peminjaman_asset'])
            ->has('log_peminjaman_asset')
            ->whereBetween('tanggal_peminjaman', [$this->start_date, $this->end_date]);

        return $query;
    }

    public function map($item): array
    {
        $this->temp_row_num++;
        $data = [];
        $t = [
            'start' => $this->temp_row_num,
            'end' => null,
        ];
        foreach ($item->log_peminjaman_asset as $key => $log) {
            $name = '-';
            if (config('app.sso_siska')) {
                $user = $log->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($log->created_by);
                $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            } else {
                $user = $log->created_by == null ? null : $this->userQueryServices->findById($log->created_by);
                $name = isset($user) ? $user->name : 'Not Found';
            }

            $find_detail_asset = DetailPeminjamanAsset::with(['asset'])->where('id_peminjaman_asset', $log->peminjaman_asset_id)->get();
            $element = '';
            foreach ($find_detail_asset as $index => $item_asset) {
                if ($index >= 1) {
                    $element .= ', ';
                }
                $element .= $item_asset->asset->kode_asset . ' (' . $item_asset->asset->deskripsi . ')';
            }

            if ($key == 0) {
                $data[] = [
                    $this->number += 1,
                    $item->code,
                    $element,
                    $log->created_at,
                    $name,
                    $log->log_message,
                ];
            } else {
                $data[] = [
                    '',
                    '',
                    '',
                    $log->created_at,
                    $name,
                    $log->log_message,
                ];
                $this->temp_row_num++;
                $end = $this->temp_row_num;
                $t['end'] = $end;
            }
        }

        $this->array_merge_cell['no'][] = $t;
        $this->array_merge_cell['code'][] = $t;
        $this->array_merge_cell['asset'][] = $t;

        return $data;
    }

    public function headings(): array
    {
        return [
            '#',
            'Kode Peminjaman',
            'Asset Dalam Peminjaman Ini',
            'Tanggal',
            'Pembuat',
            'Log',
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
                $event->sheet->getStyle('A1:F1')->applyFromArray([
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

                foreach ($this->array_merge_cell['asset'] as $key => $value) {
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
