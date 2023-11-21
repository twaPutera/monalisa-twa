<?php

namespace App\Exports\SheetAsset;

use App\Models\Lokasi;
use App\Models\Vendor;
use App\Models\KelasAsset;
use App\Models\SatuanAsset;
use App\Models\KategoriAsset;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class DataAssetSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $status;
    protected $selects;
    protected $row_count;
    protected $column_count;

    public function __construct()
    {
        $status = ['bagus', 'rusak', 'maintenance', 'tidak-lengkap', 'pengembangan'];
        $kategori_asset = KategoriAsset::pluck('kode_kategori')->toArray();
        $kodeakun = KelasAsset::pluck('no_akun')->toArray();
        $kodesatuan = SatuanAsset::pluck('kode_satuan')->toArray();
        $kodevendor = Vendor::pluck('kode_vendor')->toArray();
        $kodelokasi = Lokasi::pluck('kode_lokasi')->toArray();
        // $kategori_asset = KategoriAsset::get(['kode_kategori', 'nama_kategori'])
        //     ->map(function ($kategori) {
        //         $shortenedWords = array_map(function ($word) {
        //             $lettersOnly = preg_replace('/[^a-zA-Z]/', '', $word);
        //             return substr($lettersOnly, 0, 1);
        //         }, explode(' ', $kategori['nama_kategori']));
        //         $shortenedString = implode('', $shortenedWords);

        //         return $kategori['kode_kategori'] . '--' . strtoupper($shortenedString);
        //     })
        //     ->toArray();
        // $kodeakun = KelasAsset::get(['no_akun', 'nama_kelas'])
        //     ->map(function ($kelas) {
        //         $shortenedWords = array_map(function ($word) {
        //             $lettersOnly = preg_replace('/[^a-zA-Z]/', '', $word);
        //             return substr($lettersOnly, 0, 1);
        //         }, explode(' ', $kelas['nama_kelas']));
        //         $shortenedString = implode('', $shortenedWords);

        //         return $kelas['no_akun'] . '--' . strtoupper($shortenedString);
        //     })
        //     ->toArray();
        // $kodesatuan = SatuanAsset::pluck('kode_satuan')->toArray();
        // $kodevendor = Vendor::get(['kode_vendor', 'nama_vendor'])
        //     ->map(function ($vendor) {
        //         $shortenedWords = array_map(function ($word) {
        //             $lettersOnly = preg_replace('/[^a-zA-Z]/', '', $word);
        //             return substr($lettersOnly, 0, 1);
        //         }, explode(' ', $vendor['nama_vendor']));
        //         $shortenedString = implode('', $shortenedWords);

        //         return $vendor['kode_vendor'] . '--' . strtoupper($shortenedString);
        //     })
        //     ->toArray();
        // $kodelokasi = Lokasi::get(['kode_lokasi', 'nama_lokasi'])
        //     ->map(function ($lokasi) {
        //         $shortenedWords = array_map(function ($word) {
        //             $lettersOnly = preg_replace('/[^a-zA-Z]/', '', $word);
        //             return substr($lettersOnly, 0, 1);
        //         }, explode(' ', $lokasi['nama_lokasi']));
        //         $shortenedString = implode('', $shortenedWords);

        //         return $lokasi['kode_lokasi'] . '--' . strtoupper($shortenedString);
        //     })
        //     ->toArray();

        // dd($kategori_asset, $kodeakun, $kodesatuan, $kodevendor, $kodelokasi);

        $sparepart = ['iya', 'tidak'];
        $status_it = ['IT', 'Asset'];
        $status_peminjaman = ['iya', 'tidak'];
        $status_perolehan = ['PO', 'Hibah Eksternal', 'Hibah Penelitian', 'Hibah Perorangan','UMK','CC','Reimburse'];
        $selects = [  //selects should have column_name and options
            ['columns_name' => 'A', 'options' => $kodeakun],
            ['columns_name' => 'H', 'options' => $status_perolehan],
            ['columns_name' => 'N', 'options' => $kodesatuan],
            ['columns_name' => 'L', 'options' => $kodevendor],
            ['columns_name' => 'O', 'options' => $kodelokasi],
            ['columns_name' => 'M', 'options' => $kategori_asset],
            ['columns_name' => 'R', 'options' => $status],
            ['columns_name' => 'T', 'options' => $sparepart],
            ['columns_name' => 'U', 'options' => $status_it],
            ['columns_name' => 'S', 'options' => $status_peminjaman],
        ];
        $this->selects = $selects;
        $this->row_count = 10000; //number of rows that will have the dropdown
        $this->column_count = 5; //number of columns to be auto sized
    }

    public function collection()
    {
        $data_asset = collect([
            [
                'no_akun' => 'Akun001 (Diambil dari Sheet Kode Akun)',
                'kode_asset' => 'Asset001',
                'no_urut' => '2022',
                'deskripsi' => 'Contoh Asset',
                // 'tanggal_register' => '22-06-2022',
                'tanggal_perolehan' => '25/08/2022',
                'nilai_perolehan' => '250000',
                'tgl_pelunasan' => '25/09/2022',
                'jenis_perolehan' => 'PO',
                // 'nilai_buku_asset' => '12000',
                // 'no_memo' => '4123/UP-SU3/MEMO/AK.00/X/2022',
                'no_po' => '4123/UP-SU3/PO/AK.00/X/2022',
                'no_sp3' => '4123/UP-SU3/SP3/AK.00/X/2022',
                'no_seri' => '1123221',
                'kode_vendor' => 'Vendor001 (Diambil dari Sheet Kode Vendor)',
                'kode_jenis' => 'JenisAsset001 (Diambil dari Sheet Kode Jenis Asset)',
                'kode_satuan' => 'Satuan001 (Diambil dari Sheet Kode Satuan Asset)',
                'kode_lokasi' => 'Lokasi001 (Diambil dari Sheet Kode Lokasi Asset)',
                'spesifikasi' => 'Contoh Spesifikasi Asset',
                'cost_center' => 'Contoh Cost Center',
                // 'call_center' => '(0362) 22167',
                'kondisi' => 'bagus',
                'peminjaman' =>  'iya',
                'sparepart' => 'tidak',
                'barang_it' => 'IT',
                'notif' => '(Ini Adalah Contoh Pengisian Data, Hapus Baris Ini Sebelum Mengisi Data)',
            ],
        ]);
        return $data_asset;
    }

    public function title(): string
    {
        return 'Data Asset Baru';
    }

    public function headings(): array
    {
        return [
            'Nomor Akun Asset',
            'Kode Asset *',
            'No Urut Asset',
            'Deskripsi Asset *',
            // 'Tanggal Register',
            'Tanggal Perolehan (Format: d/m/Y)*',
            'Nilai Perolehan *',
            'Tanggal Pelunasan (Format: d/m/Y)*',
            'Jenis Perolehan (Opsi: PO/Hibah Eksternal/Hibah Penelitian/Hibah Perorangan/UMK/CC/Reimburse) *',
            // 'Nilai Buku Asset *',
            // 'No Memorandum',
            'No PO',
            'No SP3',
            'No Seri Asset',
            // 'Nilai Depresiasi',
            'Kode Vendor Asset',
            'Kode Jenis Asset *',
            'Kode Satuan Asset *',
            'Kode Lokasi Asset',
            'Spesifikasi Asset *',
            'Cost Center/Asset Holder',
            // 'Call Center',
            // 'Umur Manfaat Fisikal',
            // 'Umur Manfaat Komersial',
            'Status Kondisi Asset (Opsi: bagus/rusak/maintenance/tidak-lengkap/pengembangan) *',
            'Status Peminjaman (Opsi: iya/tidak) *',
            'Status Sparepart (Opsi: iya/tidak) *',
            'Status Pemilik Barang (Opsi: IT/Asset) *',
        ];
    }

    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $row_count = $this->row_count;
                $column_count = $this->column_count;
                foreach ($this->selects as $select) {
                    $drop_column = $select['columns_name'];
                    $options = $select['options'];
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1(sprintf('"%s"', implode(',', $options)));

                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                    }
                    // set columns to autosize
                    for ($i = 1; $i <= $column_count; $i++) {
                        $column = Coordinate::stringFromColumnIndex($i);
                        $event->sheet->getColumnDimension($column)->setAutoSize(true);
                    }
                }
            },
        ];
    }
}
