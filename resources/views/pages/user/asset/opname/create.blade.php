@extends('layouts.user.master-detail')
@section('page-title', 'Add Opname')
@section('pluggin-css')
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('pluggin-js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
@endsection
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            console.log(data);
            if (data.success) {
                $('#preview-file-error').html('');
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);

                setTimeout(() => {
                    window.location.href = '{{ route('user.asset-data.detail', $asset_data->id) }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            if (!errors.success) {
                changeTextToast('toastDanger', errors.message);
                toastbox('toastDanger', 2000)
            }
            for (let key in errors) {
                let array_error_key = key.split('.');
                if (array_error_key.length < 2) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                } else {
                    let new_key = `${array_error_key[0]}[${array_error_key[1]}][${array_error_key[2]}]`;
                    let element = formElement.find(`[name="${new_key}"]`);
                    $(element).addClass('is-invalid');
                    $(`#errorJumlah-${array_error_key[1]}`).text(errors[key][0]).show();
                }
                let element = formElement.find(`[name=${key}]`);
                clearValidation(element);
                showValidation(element, errors[key][0]);
                if (key == "gambar_asset") {
                    $('#preview-file-error').html(errors[key][0]);
                }
            }
        });
        $(document).ready(function() {
            getDataOptionSelect();
            setTimeout(() => {
                generateSelect2Lokasi();
                select2StatusAkunting();
                select2StatusKondisi();
            }, 200);
        });

        const selectServicePerencanaan = (v) => {
            const perencanaanService = $('#perencanaanService');
            if (v == "aktif") {
                perencanaanService.removeClass('d-none');
            } else if (v == "nonaktif") {
                perencanaanService.addClass('d-none');
            } else {
                perencanaanService.addClass('d-none');
            }
        }
        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });
        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        const getDataOptionSelect = () => {
            $.ajax({
                url: "{{ route('user.pengaduan.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const select = $('.select2Lokasi');
                    select.empty();
                    response.data.forEach(element => {
                        let selected = '';
                        @if ($asset_data->id_lokasi)
                            if (element.id == "{{ $asset_data->lokasi->id }}") {
                                selected = 'selected';
                            }
                        @endif
                        select.append(
                            `<option ${selected} value="${element.id}">${element.text}</option>`
                        );
                    });
                }
            })
        }

        const generateSelect2Lokasi = () => {
            $('.select2Lokasi').select2({
                'placeholder': 'Pilih Lokasi',
                'allowClear': true,
                'width': '100%'
            })
        }
        const submitForm = () => {
            $('.form-submit').submit();
        }

        const changeStatusKondisiAsset = (value, id) => {
            const targetSelect = $('#' + id);

            if (value == "tidak-ditemukan") {
                targetSelect.find('option[value="TX"]').prop('selected', true);
            }

            if (value == "bagus") {
                targetSelect.find('option[value="DB"]').prop('selected', true);
            }

            if (value == "rusak") {
                targetSelect.find('option[value="TR"]').prop('selected', true);
            }

            if (value == "tidak-lengkap") {
                targetSelect.find('option[value="DL"]').prop('selected', true);
            }

            if (value == "pengembangan") {
                targetSelect.find('option[value="TT"]').prop('selected', true);
            }

            if (value == "maintenance") {
                targetSelect.find('option[value="TP"]').prop('selected', true);
            }

            select2StatusAkunting();
        }

        const select2StatusAkunting = () => {
            $('#status_akunting').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Pilih Status Akunting',
            });
        }

        const select2StatusKondisi = () => {
            $('#status_kondisi').select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Pilih Status Kondisi',
            });
        }
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.asset-data.detail', $asset_data->id) }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <form action="{{ route('user.asset-data.opname.store', $asset_data->id) }}" class="form-submit
        " method="POST">
        @csrf
        <div class="section mt-2">
            <h2>{{ $asset_data->deskripsi }}</h2>

            <div class="mt-2">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Tanggal Opname</strong></label>
                        <input type="date" name="tanggal_opname" value="{{ date('Y-m-d') }}" class="form-control"
                            id="" placeholder="Text Input">
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                            </ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Opsi Perencanaan Service</strong></label>
                        <select name="status_perencanaan" class="form-control mr-3" id="status_perencanaan"
                            onchange="selectServicePerencanaan(this.value)">
                            <option value="nonaktif">Tidak Aktif</option>
                            <option value="aktif">Aktif</option>
                        </select>
                    </div>
                </div>
                <div id="perencanaanService" class="d-none">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="text-dark" for=""><strong>Tanggal Perencanaan Service</strong></label>
                            <input type="date" value="{{ date('Y-m-d') }}" name="tanggal_services" class="form-control"
                                id="" placeholder="Text Input">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                                </ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="text-dark" for=""><strong>Catatan Perencanaan Service</strong></label>
                            <textarea name="keterangan_services" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for="lokasi-select"><strong>Lokasi Asset</strong></label>
                        <select name="id_lokasi" class="form-control py-3 select2Lokasi" id="lokasi-select">

                        </select>
                    </div>
                </div>

                <div class="form-group boxed">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span>Status Kondisi Terakhir </span> <br>
                        </div>
                        @if ($asset_data->status_kondisi == 'bagus')
                            <div class="badge badge-success">Bagus</div>
                        @elseif($asset_data->status_kondisi == 'rusak')
                            <div class="badge badge-danger">Rusak</div>
                        @elseif($asset_data->status_kondisi == 'maintenance')
                            <div class="badge badge-warning">Maintenance</div>
                        @elseif($asset_data->status_kondisi == 'pengembangan')
                            <div class="badge badge-info">Pengembangan</div>
                        @elseif($asset_data->status_kondisi == 'tidak-ditemukan')
                            <div class="badge badge-secondary">Tidak Ditemukan</div>
                        @else
                            <div class="badge badge-dark">Tidak Lengkap</div>
                        @endif
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Status Kondisi Aset</strong></label>
                        <select name="status_kondisi" class="form-control mr-3"
                            onchange="changeStatusKondisiAsset(this.value,'status_akunting')" id="status_kondisi">
                            <option value="bagus">Bagus</option>
                            <option value="rusak">Rusak</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="tidak-lengkap">Tidak Lengkap</option>
                            <option value="pengembangan">Pengembangan</option>
                            <option value="tidak-ditemukan">Tidak Ditemukan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for="status_akunting"><strong>Status Akunting Aset</strong></label>
                        <select name="status_akunting" class="form-control mr-3" id="status_akunting">
                            @foreach ($list_status as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for="asset-select"><strong>Tingkat Kritikal</strong></label>
                        <select name="kritikal" class="form-control">
                            <option value="10">High</option>
                            <option value="5">Medium</option>
                            <option value="1">Low</option>
                        </select>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Gambar Asset Terbaru</strong></label>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span id="preview-file-text">No File Choosen</span> <br>
                                <span id="preview-file-error" class="text-danger"></span>
                            </div>
                            <label for="gambar_asset" class="btn btn-primary">
                                Upload
                                <input type="file" id="gambar_asset" accept=".jpeg,.png,.jpg,.gif,.svg"
                                    class="d-none" name="gambar_asset">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Catatan</strong></label>
                        <textarea name="catatan" class="form-control" id="" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('button-menu')
    <div class="d-flex justify-content-center">
        <a href="{{ route('user.asset-data.detail', $asset_data->id) }}"
            class="btn btn-danger border-radius-sm px-3 me-2">
            <span class="">Batal</span>
        </a>
        <button class="btn btn-success border-radius-sm px-3" onclick="submitForm()" type="submit">
            <span class="">Simpan</span>
        </button>
    </div>
@endsection
