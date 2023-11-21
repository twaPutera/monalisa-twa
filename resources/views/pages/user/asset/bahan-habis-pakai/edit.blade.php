@extends('layouts.user.master-detail')
@section('page-title', 'Ubah Permintaan Bahan Habis Pakai')
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
                    window.location.href = '{{ route('user.asset-data.bahan-habis-pakai.index') }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            console.log(errors);
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
            }
        });

        $(document).ready(function() {
            generateSelect2GroupKategori();
            generateSelect2Kategori();
            generateAutoSelect();
        });
    </script>
    <script>
        const generateSelect2GroupKategori = () => {
            $('#kelompokAsset').select2({
                'placeholder': 'Pilih Kategori Bahan Habis Pakai',
                'allowClear': true,
                'width': '100%',
                'ajax': {
                    'url': "{{ route('user.asset-data.bahan-habis-pakai.kategori.get-data-select2') }}",
                    'dataType': 'json',
                    'data': function(params) {
                        return {
                            'keyword': params.term,
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

        const generateSelect2Kategori = () => {
            $('#jenisAsset').select2({
                'placeholder': 'Pilih Asset',
                'allowClear': true,
                'width': '100%',
                'ajax': {
                    'url': "{{ route('user.asset-data.bahan-habis-pakai.item.get-data-select2') }}",
                    'dataType': 'json',
                    'data': function(params) {
                        return {
                            'keyword': params.term,
                            'id_kategori_inventori': $('#kelompokAsset').val()
                        }
                    },
                    'processResults': function(response) {
                        return {
                            'results': response.data
                        }
                    }
                }
            }).on('select2:selecting', function(e) {
                let data = e.params.args.data;
                $('#detailPeminjamanContainer').append(generateTemplateDetailPeminjaman(data));
            }).on('select2:unselecting', function(e) {
                let data = e.params.args.data;
                $(`#${data.id}`).remove();
            });
        }

        const generateAutoSelect = () => {
            $.ajax({
                url: '{{ route('user.asset-data.bahan-habis-pakai.item.get-data-select2') }}',
                type: 'GET',
                data: {
                    id_kategori_inventori: $('#kelompokAsset').val()
                },
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        let option = '';
                        data.forEach(element => {
                            option += `<option value="${element.id}">${element.text}</option>`;
                        });
                        $('#jenisAsset').append(option);

                        @foreach ($edit->detail_request_inventori as $data)
                            $('#jenisAsset option[value="{{ $data->inventori_id }}"]').prop('selected',
                                'selected');

                            var update = {
                                id: "{{ $data->inventori_id }}",
                                text: "{{ $data->inventori->nama_inventori }}" + "(" +
                                    "{{ $data->inventori->kode_inventori }}" + ")",
                                qty: "{{ $data->qty }}"
                            }
                            $('#detailPeminjamanContainer').append(generateTemplateDetailPeminjamanUpdate(
                                update));
                        @endforeach
                    }
                }
            })
        }

        const generateTemplateDetailPeminjaman = (item) => {
            return `
                <div class="row mb-2" id="${item.id}">
                    <div class="col-8">
                        <input type="text" value="${item.text}" readonly name="data_bahan_habis_pakai[${item.id}][nama_item]" class="form-control py-3">
                    </div>
                    <div class="col-4">
                        <input type="number" value="1" name="data_bahan_habis_pakai[${item.id}][jumlah]" class="form-control py-3">
                    </div>
                    <div class="col-12">
                        <div class="invalid-feedback" id="errorJumlah-${item.id}"></div>
                    </div>
                </div>
            `;
        }

        const generateTemplateDetailPeminjamanUpdate = (item) => {
            return `
                <div class="row mb-2" id="${item.id}">
                    <div class="col-8">
                        <input type="text" value="${item.text}" readonly name="data_bahan_habis_pakai[${item.id}][nama_item]" class="form-control py-3">
                    </div>
                    <div class="col-4">
                        <input type="number" value="${item.qty}" name="data_bahan_habis_pakai[${item.id}][jumlah]" class="form-control py-3">
                    </div>
                    <div class="col-12">
                        <div class="invalid-feedback" id="errorJumlah-${item.id}"></div>
                    </div>
                </div>
            `;
        }

        const submitForm = () => {
            $('.form-submit').submit();
        }

        const generateOptionUnitKerjaAndJabatan = () => {
            $.ajax({
                url: "{{ route('user.dashboard.profile.find-position-by-username') }}",
                method: "GET",
                data: {
                    username: "{{ $user->username_sso }}"
                },
                success: function(response) {
                    if (response.success) {
                        $('#unitKerjaSelect').empty();
                        $('#jabatanSelect').empty();
                        $(response.data).each(function(index, item) {
                            let unit_kerja = "{{ $edit->unit_kerja }}";
                            let jabatan = "{{ $edit->jabatan }}";
                            $('#unitKerjaSelect').append(`
                                <option value="${item.unit_kerja}">${item.unit_kerja}</option>
                            `);
                            $('#jabatanSelect').append(`
                                <option value="${item.position}">${item.position}</option>
                            `);
                        })

                    }
                }
            })
        }

        $(document).ready(function() {
            generateOptionUnitKerjaAndJabatan();
            $('#unitKerjaSelect').select2({
                placeholder: "Pilih Unit Kerja",
                tags: true,
            });

            $('#jabatanSelect').select2({
                placeholder: "Pilih Jabatan",
                tags: true,
            });
        })
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <div class="section mt-2">
        <form action="{{ route('user.asset-data.bahan-habis-pakai.update', $edit->id) }}" method="POST"
            class="mt-2 form-submit">
            @csrf
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for="kelompokAsset"><strong>Kategori Bahan Habis Pakai</strong></label>
                    <select name="" class="form-control py-3" id="kelompokAsset">

                    </select>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for="jenisAsset"><strong>Item Bahan Habis Pakai</strong></label>
                    <select name="id_bahan_habis_pakai[]" class="form-control py-3" multiple id="jenisAsset">

                    </select>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper p-1 border border-primary border-radius-sm">
                    <label class="text-dark" for="jenisAsset"><strong>Detail Permintaan</strong></label>
                    <div id="detailPeminjamanContainer">

                    </div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Unit Kerja</strong></label>
                    <select name="unit_kerja" id="unitKerjaSelect" class="form-control py-3">
                        <option value="{{ $edit->unit_kerja }}">{{ $edit->unit_kerja }}</option>
                    </select>
                    <small>Jika tidak terdapat unit kerja silahkan ketikkan unit kerja Anda dan tekan "enter"</small>
                    {{-- <input type="text" name="unit_kerja" class="form-control" id=""> --}}
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Jabatan</strong></label>
                    <select name="jabatan" id="jabatanSelect" class="form-control py-3">
                        <option value="{{ $edit->jabatan }}">{{ $edit->jabatan }}</option>
                    </select>
                    <small>Jika tidak terdapat jabatan silahkan ketikkan jabatan Anda dan tekan "enter"</small>
                    {{-- <input type="text" name="jabatan" class="form-control" id="jabatanSelect"> --}}
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>No Memo</strong></label>
                    <input type="text" name="no_memo" class="form-control" value="{{ $edit->no_memo }}" id="">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Tanggal Pengambilan</strong></label>
                    <div class="d-flex">
                        <div class="" style="width: 100%;">
                            <input type="date" name="tanggal_pengambilan" value="{{ $edit->tanggal_pengambilan }}"
                                class="form-control pe-1" id="" placeholder="Text Input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Alasan Permintaan</strong></label>
                    <textarea name="alasan_permintaan" class="form-control" id="" cols="30" rows="10">{{ $edit->alasan }}</textarea>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('button-menu')
    <div class="d-flex justify-content-center">
        <a class="btn btn-danger border-radius-sm px-3 me-2" href="{{ route('user.asset-data.peminjaman.index') }}">
            <span class="">Batal</span>
        </a>
        <button onclick="submitForm()" class="btn btn-success border-radius-sm px-3" type="button">
            <span class="">Simpan</span>
        </button>
    </div>
@endsection
