@component('mail::message')
# Introduction

Peminjaman dengan kode {{ $peminjaman->code }} akan sudah jatuh tempo pada tanggal {{ $peminjaman->due_date }}.
Harap segera mengembalikan aset yang dipinjam dengan rincian sebagai berikut:

@component('mail::table')

| Nama Aset | Kode Aset | Kategori Aset |
| :--- | :--- | :--- |
@foreach($detail_peminjaman_asset as $detail)

<?php
    $asset = json_decode($detail->json_asset_data);
    $kategori_asset = App\Models\KategoriAsset::find($asset->id_kategori_asset);
?>

| {{ $asset->deskripsi }} | {{ $asset->kode_asset }} | {{ $kategori_asset->nama_kategori }} |

@endforeach

@endcomponent

@component('mail::button', ['url' => $url])
Lihat Peminjaman
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
