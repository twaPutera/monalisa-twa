<?php

namespace App\Exports;

use App\Models\GroupKategoriAsset;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DepresiasiExport implements WithMultipleSheets
{
    use Exportable;

    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];

        $groups = GroupKategoriAsset::all();

        foreach ($groups as $group) {
            $sheets[] = new DepresiasiPerKelompokSheet($this->year, $group);
        }

        return $sheets;
    }
}
