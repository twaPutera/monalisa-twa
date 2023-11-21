@extends('layouts.print.index')
@section('css')
    <style>
        p, td {
            font-weight: normal;
            font-size: 16px;
            line-height: 20px;
        }
    </style>
@endsection
@section('js')
<script type="text/php">
	if (isset($pdf)) {
		$x = 50;
		$y = 795;
		$text = "{PAGE_NUM}";
		$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold");
		$size = 12;
		$color = array(0,0,0);
		$word_space = 0.0;  //  default
		$char_space = 0.0;  //  default
		$angle = 0.0;   //  default
		$pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
	}
</script>
@endsection

@section('header')
<header>
	<div class="text-center">
		<img src="{{ public_path('assets/images/logo-Press-103x75.png') }}" alt="">
	</div>
</header>
@endsection
@section('footer')
<footer>
	<img src="{{ public_path('assets/images/footer-surat.png') }}" alt="" width="100%">
</footer>
@endsection

@section('body')
<div style="text-align: center;">
    <h6 style="text-decoration: underline; margin-bottom: 0;"><strong>BERITA ACARA SERAH TERIMA BARANG</strong></h6>
    <p>Nomor: {{ $data->no_surat }}</p>
</div>
<div style="margin-top: 20px;">
    <p style="text-align: justify;">Pada hari ini {{ App\Helpers\DateIndoHelpers::getDayName($data->tanggal_pemindahan) }} tanggal {{ App\Helpers\DateIndoHelpers::formatDateToIndo($data->tanggal_pemindahan) }} bertempat di Universitas Pertamina telah dilakukan penyerahan dan penerimaan barang sebagai fasilitas operasional :</p>
</div>

<div style="margin-top: 20px;">
    <table style="width: 100%">
        <tr>
            <td style="width: 100px;">Nama</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penyerah->nama }}</td>
        </tr>
        <tr>
            <td style="width: 100px;">Jabatan</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penyerah->jabatan }}</td>
        </tr>
        <tr>
            <td style="width: 100px;">NIP</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penyerah->no_induk }}</td>
        </tr>
    </table>
</div>
<div style="margin-top: 20px;">
    <p style="text-align: justify;">Selaku penanggungjawab {{ $penyerah->unit_kerja }} yang selanjutnya disebut PIHAK PERTAMA, dan</p>
</div>

<div style="margin-top: 20px;">
    <table style="width: 100%">
        <tr>
            <td style="width: 100px;">Nama</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penerima->nama }}</td>
        </tr>
        <tr>
            <td style="width: 100px;">Jabatan</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penerima->jabatan }}</td>
        </tr>
        <tr>
            <td style="width: 100px;">NIP</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penerima->no_induk }}</td>
        </tr>
        <tr>
            <td style="width: 100px;">No HP</td>
            <td style="width: 10px;">:</td>
            <td> </td>
        </tr>
        <tr>
            <td style="width: 100px;">Email</td>
            <td style="width: 10px;">:</td>
            <td> {{ $penerima->email }}</td>
        </tr>
    </table>
</div>

<div style="margin-top: 20px;">
    <p style="text-align: justify;">yang selanjutnya disebut PIHAK KEDUA.</p>
</div>

<div style="margin-top: 20px;">
    <p style="text-align: justify;">Dengan ini, PIHAK PERTAMA menyerahkan barang kepada PIHAK KEDUA dan PIHAK KEDUA menyatakan telah menerima barang tersebut dari PIHAK PERTAMA berupa :</p>
</div>

<div style="margin-top: 20px;">
    <table style="width: 100%; border: 1px solid #000;">
        <tr>
            <td style="text-align: center; font-weight: bold; padding: 5px; width: 30px; border: 1px solid #000;">No.</td>
            <td style="text-align: center; border: 1px solid #000; padding: 5px; font-weight: bold;">Nama Barang</td>
            <td style="text-align: center; border: 1px solid #000; padding: 5px; font-weight: bold;">Serial Number</td>
            <td style="text-align: center; border: 1px solid #000; padding: 5px; font-weight: bold;">Jumlah Barang</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">1</td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $asset->deskripsi }}</td>
            <td style="border: 1px solid #000; padding: 5px;">SN : {{ $asset->no_seri }}</td>
            <td style="border: 1px solid #000; padding: 5px;">1 Unit</td>
        </tr>
    </table>
</div>

<div style="margin-top: 20px;">
    <p style="text-align: justify;">Jika terjadi kerusakan karena <em>human error</em> seperti kelalaian pengguna, instalasi <em>software illegal</em> dan lain sebagainya maka segala biaya yang timbul akan menjadi tanggung jawab PIHAK KEDUA</p>
</div>

<div style="margin-top: 20px;">
    <p style="text-align: justify;">Demikianlah berita acara serah terima barang ini dibuat dan telah disetujui oleh kedua belah pihak.</p>
</div>

<div style="margin-top: 40px;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 70%;">
                <p>PIHAK PERTAMA</p>
                <div style="height: 120px"></div>
                <p>{{ $penyerah->nama }}</p>
            </td>
            <td style="width: 30%;">
                <p>PIHAK KEDUA</p>
                <div style="height: 120px">
                    @if(isset($data->approval[0]->qr_path))
                        <img src="{{ storage_path('app/images/qr-code/pemindahan/' . $data->approval[0]->qr_path) }}" alt="" style="width: 90px; height: 90px;">
                    @endif
                </div>
                <p>{{ $penerima->nama }}</p>
            </td>
        </tr>
    </table>
</div>
@endsection