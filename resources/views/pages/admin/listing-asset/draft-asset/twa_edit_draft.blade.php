@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link href="{{ asset('assets/vendors/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>
        div.dataTables_wrapper {
            width: 200% !important;
        }

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .wahyu td{
            background-color: #ffcc00 !important;
            color:black !important;
        }

        /* Reset Gaya Elemen Select */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            position: relative;
            padding: 0.3rem 3rem 0.65rem 1rem !important;
            line-height: 1.5 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b,
        .select2-container--default .select2-selection--multiple .select2-selection__arrow b {
            display: block !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow:before, 
        .select2-container--default .select2-selection--multiple .select2-selection__arrow:before{
            display: none !important;
        }

        .invalid-feedback {
            display: block !important;
        }

    </style>
   
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
@endsection
@section('custom_js')
    

@endsection
@section('main-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="hidden" id="jsonTempAsset" value="[]">
            </div>
            <div class="row">
                <div class="col-12" id="colTable">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><strong class="text-primary">Data Asset - Tambah Asset</strong></h5>
                        <h5 class="text-primary"><strong id="totalFilterAktif">Total 0</strong></h5>
                    </div>
                    <div class="">
                    <form class="kt-form kt-form--fit kt-form--label-right"
                        action="{{route('admin.listing-asset.update.draft',$asset_data->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$asset_data->id}}" id="idAssetDraft">
                        <div class="modal-body">
                            <div class="kt-scroll ps ps--active-y" data-scroll="true" style="overflow: hidden; height: 70vh;">
                                <div class="row">
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Kode Asset</label>
                                        <input value="{{$asset_data->kode_asset}}" type="text" class="form-control" name="kode_asset">
                                        @error('kode_asset')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Deskripsi / Nama</label>
                                        <input value="{{$asset_data->deskripsi}}" type="text" class="form-control" name="deskripsi">
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Nomor Seri</label>
                                        <input value="{{$asset_data->no_seri}}" type="text" class="form-control" name="no_seri">
                                        @error('no_seri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Kelompok Aset</label>
                                        <select name="id_group_asset" class="form-control" id="groupAssetCreate">
                                            <option value="" disabled selected>Pilih Kelompok</option>
                                            @foreach($listKelompokAsset as $row)
                                            <option value="{{$row->id}}" <?php if($id_group_kategori_asset == $row->id){ echo 'selected'; } ?> >
                                                {{$row->nama_group}}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('id_group_asset')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Jenis Asset</label>
                                        <select name="id_kategori_asset" onchange="getNoUrutByKelompok(this)" class="form-control" id="kategoriAssetCreate">
                                            <option value="" disabled selected>Pilih Jenis</option>
                                            @foreach($jenis_asset as $row)
                                            <option value="{{$row->id}}" {{ $asset_data->id_kategori_asset == $row->id ? 'selected' : '' }}>
                                                {{$row->nama_kategori}}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('id_kategori_asset')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Nomor Urut</label>
                                        <input value="{{$asset_data->no_urut}}" type="text" class="form-control" name="no_urut">
                                        @error('no_urut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Tanggal Perolehan</label>
                                        <input value="{{ $asset_data->tanggal_perolehan }}" type="date" class="form-control datepickerCreate" name="tanggal_perolehan">
                                        @error('tanggal_perolehan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Nilai Perolehan (Rp)</label>
                                        <input value="{{ $asset_data->nilai_perolehan }}" type="number" class="form-control" name="nilai_perolehan">
                                        @error('nilai_perolehan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Tanggal Pelunasan</label>
                                        <input type="date" class="form-control datepickerCreate"
                                            name="tanggal_pelunasan" value="{{ $asset_data->tgl_pelunasan }}">
                                        @error('tanggal_pelunasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Vendor</label>
                                        <select name="id_vendor" class="form-control" id="vendorAssetCreate">
                                            <option value="" disabled selected>Pilih Vendor</option>
                                            @foreach($vendors as $row)
                                            <option value="{{$row->id}}" {{ $asset_data->id_vendor == $row->id ? 'selected' : '' }}>{{$row->nama_vendor}}</option>
                                            @endforeach
                                        </select>
                                        @error('id_vendor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Lokasi Asset</label>
                                        <select name="id_lokasi" class="form-control" id="lokasiAssetCreate">
                                        <option value="" disabled selected>Pilih Lokasi</option>
                                            @foreach($lokasi as $row)
                                            <option value="<?php echo $row["id"] ?>" {{$asset_data->id_lokasi == $row['id'] ? 'selected' : '' }}><?php echo $row["text"] ?></option>
                                            @endforeach
                                        </select>
                                        @error('id_lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Fungsi</label>
                                        <select name="unit_kerja" class="form-control" id="unit_kerja">
                                        <option value="" selected>Pilih Fungsi</option>
                                            @foreach($unit_kerja as $row)
                                            <option value="{{$row->id}}" {{ $asset_data->id_unit_kerja == $row->id ? 'selected' : '' }}>{{$row->nama_unit_kerja}}</option>
                                            @endforeach
                                        </select>
                                        @error('unit_kerja')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                  
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Jenis Perolehan</label>
                                        <!-- bagian ini telah diupdate oleh wahyu -->
                                        <select class="form-control" onchange="jenisAssetChange(this)" name="jenis_penerimaan">
                                            <option value="" {{ $asset_data->jenis_penerimaan == '' ? 'selected' : '' }}>Pilih Jenis Perolehan</option>
                                            <option data-asset-lama="0" value="PO" {{ $asset_data->jenis_penerimaan == 'PO' ? 'selected' : '' }}>PO</option>
                                            <option data-asset-lama="0" value="Hibah Eksternal" {{ $asset_data->jenis_penerimaan == 'Hibah Eksternal' ? 'selected' : '' }}>Hibah Eksternal</option>
                                            <option data-asset-lama="0" value="Hibah Penelitian" {{ $asset_data->jenis_penerimaan == 'Hibah Penelitian' ? 'selected' : '' }}>Hibah Penelitian</option>
                                            <option data-asset-lama="0" value="Hibah Perorangan" {{ $asset_data->jenis_penerimaan == 'Hibah Perorangan' ? 'selected' : '' }}>Hibah Perorangan</option>
                                            <option data-asset-lama="1" value="Dari Asset Lama" {{ $asset_data->jenis_penerimaan == 'Dari Asset Lama' ? 'selected' : '' }}>Dari Asset Lama</option>
                                            <option data-asset-lama="0" value="UMK" {{ $asset_data->jenis_penerimaan == 'UMK' ? 'selected' : '' }}>UMK</option>
                                            <option data-asset-lama="0" value="CC" {{ $asset_data->jenis_penerimaan == 'CC' ? 'selected' : '' }}>CC</option>
                                            <option data-asset-lama="0" value="Reimburse" {{ $asset_data->jenis_penerimaan == 'Reimburse' ? 'selected' : '' }}>Reimburse</option>
                                        </select>
                                        @error('jenis_penerimaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Satuan</label>
                                        <select name="id_satuan_asset" class="form-control" id="satuanAssetCreate">
                                        <option value="" disabled selected>Pilih Satuan</option>
                                            @foreach($satuan as $row)
                                            <option value="{{$row->id}}" {{ $asset_data->id_satuan_asset == $row->id ? 'selected' : '' }}>{{$row->nama_satuan}}</option>
                                            @endforeach
                                        </select>
                                        @error('id_satuan_asset')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                  
                                    <div class="form-group col-md-4 col-6">
                                        <label for="">Asset Holder</label>
                                        <select name="ownership" class="form-control" id="ownershipAssetCreate" >
                                        <option value="" selected>Pilih Ownership</option>
                                            @foreach($ownership as $row)
                                                <option value="{{$row->id}}" {{ $asset_data->ownership == $row->id ? 'selected' : '' }}>{{$row->name}} - {{$row->email}}</option>
                                            @endforeach
                                            
                                        </select>
                                        @error('ownership')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group col-md-4 col-6">
                                        <label for="">Nilai Buku (Rp)</label>
                                        <input type="number" class="form-control" name="nilai_buku_asset">
                                    </div> --}}
                                    <div class="form-group col-md-4 col-6" id="asal-asset-container" style="display: none;">
                                        <label for="">Asal Asset</label>
                                        <input type="text" class="form-control" readonly id="asal_asset_preview_edit">
                                        <input type="hidden" class="form-control" name="asal_asset"
                                            id="asal_asset_id_edit">
                                        @error('asal_asset')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-md-4 col-6 d-flex">
                                                <div class="d-flex align-items-center mr-4">
                                                    <span class="kt-switch kt-switch--sm kt-switch--icon">
                                                        <label class="mb-0">
                                                            <input type="checkbox" value="1" name="is_sparepart" >
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                    <span class="ml-4">Sparepart</span>
                                                </div>
                                                <div class="d-flex align-items-center mr-4">
                                                    <span class="kt-switch kt-switch--sm kt-switch--icon">
                                                        <label class="mb-0">
                                                            <input type="checkbox" value="1" name="is_pinjam">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                    <span class="ml-4">Dapat Dipinjam</span>
                                                </div>
                                                <div class="d-flex align-items-center mr-4">
                                                    <span class="kt-switch kt-switch--sm kt-switch--icon">
                                                        <label class="mb-0">
                                                            <input type="checkbox" value="1" name="is_it">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                    <span class="ml-4">Barang IT</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-6">
                                        <div class="form-group">
                                            <label for="">Pilih Memorandum</label>
                                            <select name="status_memorandum" class="form-control mr-3" id=""
                                                onchange="changeMemorandumStatus(this.value)">
                                                {{-- <option value="draft" {{ old('status_memorandum') == 'draft' ? 'selected' : '' }}>Draft</option> --}}
                                                <option value="" disabled>Pilih Asal Memorandum</option>
                                                <option value="tidak-ada" {{ empty($asset_data->id_surat_memo_andin) && empty($asset_data->no_memo_surat) ? 'selected' : '' }}>Tidak Ada Memorandum</option>
                                                <option value="andin" {{ !empty($asset_data->id_surat_memo_andin) && !empty($asset_data->no_memo_surat) ? 'selected' : '' }}>Dari ANDIN</option>
                                                <option value="manual" {{ empty($asset_data->id_surat_memo_andin) && !empty($asset_data->no_memo_surat) ? 'selected' : '' }}>Input Manual</option>
                                            </select>
                                            @error('status_memorandum')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group d-none" id="memo_andin">
                                            <label for="">No Memorandum</label>
                                            <select name="id_surat_memo_andin" class="form-control memorandumAndin"
                                                id="">

                                            </select>
                                            <input type="hidden" id="noMemoSurat" name="no_memo_surat" value="">
                                        </div>
                                        <div class="form-group d-none" id="memo_manual">
                                            <label for="">Nomor Memorandum</label>
                                            <input value="{{ $asset_data->no_memo_surat ?? old('no_memo_surat_manual')}}" type="text" class="form-control" name="no_memo_surat_manual">
                                        </div>
                                        <div class="form-group" id="twa_nomor_po">
                                            <label for="">Nomor PO</label>
                                            <input value="{{ $asset_data->no_po ?? old('no_po')}}" type="text" class="form-control" name="no_po">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor SP3</label>
                                            <input value="{{ $asset_data->no_sp3 ?? old('no_sp3')}}" type="text" class="form-control" name="no_sp3">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <div class="form-group">
                                            <label for="">Cost Center</label>
                                            <input value="{{ $asset_data->cost_center ?? old('cost_center')}}" type="text" class="form-control" name="cost_center">
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="">Call Center</label>
                                            <input type="text" class="form-control" name="call_center">
                                        </div> --}}
                                        <div class="form-group">
                                            <label for="">Spesifikasi</label>
                                            <textarea value="{{ old('spesifikasi')}}" name="spesifikasi" class="form-control" id="" cols="30" rows="10">{{ $asset_data->spesifikasi}}</textarea>
                                            @error('spesifikasi')
                                                    <br><div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <div class="form-group">
                                            <label for="">Nomor Akun</label>
                                            <select name="id_kelas_asset" class="form-control" id="kelasAssetCreate">
                                                <option value="" disabled selected>Pilih Kelas</option>
                                                @foreach($kelas_assets as $row)
                                                    <option value="{{$row->id}}" {{ $asset_data->id_kelas_asset == $row->id ? 'selected' : '' }}>
                                                        {{$row->nama_kelas}} ({{$row->no_akun}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <label for="">Status Kondisi Aset</label>
                                                <select name="status_kondisi"
                                                    onchange="changeStatusKondisiAsset(this.value, 'status_akunting')"
                                                    class="form-control mr-3 status_kondisi" style="width: 60%;"
                                                    id="status_kondisi">
                                                    <option value="bagus" {{ $asset_data->status_kondisi == 'bagus' ? 'selected' : '' }}>Bagus</option>
                                                    <option value="rusak" {{ $asset_data->status_kondisi == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                                    <option value="maintenance" {{ $asset_data->status_kondisi == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                    <option value="tidak-lengkap" {{ $asset_data->status_kondisi == 'tidak-lengkap' ? 'selected' : '' }}>Tidak Lengkap</option>
                                                    <option value="pengembangan" {{ $asset_data->status_kondisi == 'pengembangan' ? 'selected' : '' }}>Pengembangan</option>
                                                    <option value="tidak-ditemukan" {{ $asset_data->status_kondisi == 'tidak-ditemukan' ? 'selected' : '' }}>Tidak Ditemukan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <label for="status_akunting">Status Akunting Aset</label>
                                                <select name="status_akunting" class="form-control mr-3 status_akunting"
                                                    style="width: 60%;" id="status_akunting">
                                                    @foreach ($list_status as $key => $item)
                                                        <option value="{{ $key }}" {{ $asset_data->status_akunting == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Gambar Asset Saat Ini</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="{{ route('admin.listing-asset.image.preview') . '?filename=' . $image_asset->path }}" alt="Asset Image" width="150px">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span id="preview-file-text-edit">No File
                                                        Choosen</span> <br>
                                                    <span id="preview-file-error-edit" class="text-danger"></span>
                                                </div>
                                                <label for="gambar_asset_edit" class="btn btn-primary">
                                                    Upload
                                                    <input type="file" id="gambar_asset_edit"
                                                        accept=".jpeg,.png,.jpg,.gif,.svg" class="d-none"
                                                        name="gambar_asset">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Kembali</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" ></script>

<script>
    $(document).ready(function() {
        $('#groupAssetCreate').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#kategoriAssetCreate').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#vendorAssetCreate').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#lokasiAssetCreate').select2();
    });
</script>
<script>
    $(document).ready(function() {
        $('#satuanAssetCreate').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#ownershipAssetCreate').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#unit_kerja').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#kelasAssetCreate').select2();
    });
</script>
<script>
    $(document).ready(function() {
        $('#status_akunting').select2();
    });
</script>

<script>
    $(document).ready(function () {
        // Panggil fungsi saat elemen kelompok aset berubah
        $('#groupAssetCreate').on('change', function () {
            // Ambil nilai yang dipilih
            var selectedGroup = $(this).val();

            // Panggil AJAX untuk mendapatkan jenis aset berdasarkan kelompok
            $.ajax({
                url: '{{ route('get.jenis.aset', ['group' => '__group__']) }}'.replace('__group__', selectedGroup),
                type: 'GET',
                success: function (data) {
                    // Hapus opsi lama
                    //alert(data);
                    $('#kategoriAssetCreate').empty();

                    $('#kategoriAssetCreate').append('<option value="">Pilih Jenis</option>');

                    // Tambahkan opsi baru berdasarkan data yang diterima dari server
                    $.each(data, function (key, value) {
                        //console.log(value);
                        $('#kategoriAssetCreate').append('<option value="' + value.id + '">' + value.nama_kategori + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

    $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });

        $('#gambar_asset_edit').on('change', function() {
            const file = $(this)[0].files[0];
            console.log(file.name);
            $('#preview-file-text-edit').text(file.name);
        });
</script>
<script>
    function getNoUrutByKelompok(id){
        //alert(id.value);
        var id_kategori_asset = id.value;

        $.ajax({
            url: '{{ route('admin.listing-asset.get-no-urut-by-kelompok-id.twa', ['id' => '__group__']) }}'.replace('__group__', id_kategori_asset), 
            type: 'GET',
           // data: { 'id_kategori_asset': id_kategori_asset },
            success: function (data) {
                // Set nilai nomor urut ke dalam input
                $('input[name="no_urut"]').val(data.no_urut);
                generateKodeAsset(data.no_urut);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

    }
</script>
<script>
     function generateKodeAsset(element){
        //alert(element);
           // const form = $(element).closest('form');
            const no_urut = element;
            const selectedOption = $('select[name="id_kategori_asset"]').select2('data')[0];

            if (selectedOption && selectedOption.text) {
                const kategori_asset = selectedOption.text;
                const matches = kategori_asset.match(/\(([^)]+)\)/);
                let valueInsideParentheses;
                if (matches) {
                    valueInsideParentheses = matches[1];
                }

                // Lanjutkan pemrosesan sesuai kebutuhan Anda
                const kode_asset = `${valueInsideParentheses}${no_urut !== "" ? no_urut : generateRandomString(5)}`;
                $('input[name="kode_asset"]').val(kode_asset);
            }
        }


        function generateRandomString(num) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

            for (var i = 0; i < num; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
</script>

<script>
     function resetForm() {
        // Enable both dropdowns
        document.getElementById("ownershipAssetCreate").removeAttribute("disabled");
        document.getElementById("unit_kerja").removeAttribute("disabled");

        // Reset selected values to null (or set to default value)
        document.getElementById("ownershipAssetCreate").value = null;
        document.getElementById("unit_kerja").value = null;

        // Remove "disabled" attribute from default options
        document.getElementById("ownershipAssetCreate").options[0].removeAttribute("disabled");
        document.getElementById("unit_kerja").options[0].removeAttribute("disabled");
    }

    function handleOwnershipChange(value) {
        if(value.value !=""){
            document.getElementById("unit_kerja").setAttribute("disabled", "disabled");
        }
    }

    function handleUnitKerjaChange(value) {
        // Disable ownership dropdown when unit_kerja is selected
        if(value.value !=""){
        document.getElementById("ownershipAssetCreate").setAttribute("disabled", "disabled");
        }
    }


    function changeMemorandumStatus(v) {
            const memoAndin = $('#memo_andin');
            const memoManual = $('#memo_manual');
            if (v == "andin") {
                memoAndin.removeClass('d-none');
                memoManual.addClass('d-none');
            } else if (v == "manual") {
                memoManual.removeClass('d-none');
                memoAndin.addClass('d-none');
            } else {
                memoAndin.addClass('d-none');
                memoManual.addClass('d-none');
            }
        }
</script>


@endsection
