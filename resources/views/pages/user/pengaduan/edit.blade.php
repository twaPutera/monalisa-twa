@extends('layouts.user.master-detail')
@section('page-title', 'Ubah Aduan')
@section('pluggin-css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('custom-css')
    <style>

    </style>
@endsection
@section('pluggin-js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
@endsection
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);

                setTimeout(() => {
                    window.location.href = '{{ route('user.pengaduan.index') }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            if (!errors.success) {
                changeTextToast('toastDanger', errors.message);
                toastbox('toastDanger', 2000)
            }
            for (let key in errors) {
                let element = formElement.find(`[name=${key}]`);
                clearValidation(element);
                showValidation(element, errors[key][0]);

                if (key == "file_asset_service") {
                    $('#preview-file-error').html(errors[key][0]);
                }
            }
        });

        $(document).ready(function() {
            getDataOptionSelect();

            setTimeout(() => {
                generateSelect2Lokasi();
                generateSelect2Asset();
            }, 200);
        });
    </script>
    <script>
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
                        if (element.id == $('#lokasiParentId').val()) {
                            selected = 'selected';
                        }

                        if (element.id == $('#valueLokasiDefault').val()) {
                            selected = 'selected';
                        }

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

        const generateSelect2Asset = () => {
            $('.selectAsset').select2({
                'placeholder': 'Pilih Asset',
                'allowClear': true,
                'width': '100%',
                'ajax': {
                    'url': "{{ route('user.asset-data.get-data-select2') }}",
                    'dataType': 'json',
                    'data': function(params) {
                        return {
                            'keyword': params.term,
                            'id_lokasi': $('.select2Lokasi').val()
                        }
                    },
                    'processResults': function(response) {
                        return {
                            'results': response.data
                        }
                    }
                }
            })
        }
        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });
        const submitForm = () => {
            $('.form-submit').submit();
        }
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <div class="section mt-2">
        <form action="{{ route('user.pengaduan.update', $pengaduan->id) }}" method="POST" class="mt-2 form-submit">
            @csrf
            <div class="form-group boxed">
                <div class="input-wrapper">
                    @if ($pengaduan->asset_data != null)
                        @if ($pengaduan->asset_data->id_lokasi != null)
                            <input type="hidden" id="valueLokasiDefault" value="{{ $pengaduan->asset_data->lokasi->id }}">
                        @endif
                    @else
                        <input type="hidden" id="valueLokasiDefault" value="{{ $pengaduan->lokasi->id }}">
                    @endif
                    <label class="text-dark" for="lokasi-select"><strong>Lokasi</strong></label>
                    <select name="id_lokasi" class="form-control py-3 select2Lokasi" id="lokasi-select">
                    </select>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for="asset-select"><strong>Asset</strong></label>
                    <select name="id_asset" class="form-control py-3 selectAsset" id="asset-select">
                        @if ($pengaduan->asset_data != null)
                            <option value="{{ $pengaduan->id_asset_data }}">{{ $pengaduan->asset_data->deskripsi }}
                            </option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Tanggal</strong></label>
                    <input type="date" name="tanggal_pengaduan" value="{{ $pengaduan->tanggal_pengaduan }}"
                        class="form-control" id="" placeholder="Text Input">
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                        </ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for="asset-select"><strong>Prioritas</strong></label>
                    <select name="prioritas" class="form-control">
                        <option value="10">High</option>
                        <option value="5">Medium</option>
                        <option value="1">Low</option>
                    </select>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Catatan Aduan</strong></label>
                    <textarea name="alasan_pengaduan" class="form-control" id="" cols="30" rows="10">{{ $pengaduan->catatan_pengaduan }}</textarea>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Gambar Pengaduan</strong></label>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span id="preview-file-text">No File Choosen</span> <br>
                            <span id="preview-file-error" class="text-danger"></span>
                        </div>
                        <label for="gambar_asset" class="btn btn-primary">
                            Upload
                            <input type="file" id="gambar_asset" accept=".jpeg,.png,.jpg,.gif,.svg" class="d-none"
                                name="file_asset_service">
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('button-menu')
    <div class="d-flex justify-content-center">
        <a href="{{ route('user.dashboard.index') }}" class="btn btn-danger border-radius-sm px-3 me-2">
            <span class="">Batal</span>
        </a>
        <button onclick="submitForm()" class="btn btn-success border-radius-sm px-3" type="button">
            <span class="">Simpan</span>
        </button>
    </div>
@endsection
