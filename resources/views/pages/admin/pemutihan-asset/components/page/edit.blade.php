@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        var table3 = $('.editAssetData');
        $(document).ready(function() {

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    showToastSuccess('Sukses', data.message);
                    if (data.redirect && data.redirect != null) {
                        setTimeout(function() {
                            var redirect = "{{ route('admin.pemutihan-asset.index') }}"
                            location.assign(redirect);
                        }, 1000);
                    }
                    if (data.reload && data.reload != null) {
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);
                    }
                }

            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                if (!errors.success) {
                    showToastError('Gagal', errors.message);
                }

                for (let key in errors) {
                    if (key.includes("keterangan_pemutihan_asset")) {
                        let errorAlert = $('#alert-error');
                        errorAlert.empty();
                        errorAlert.removeClass('d-none');
                        errorAlert.append("Keterangan Penghapusan Asset Wajib Diisi");
                    }
                    if (key.includes("gambar_asset")) {
                        let errorAlert = $('#alert-error');
                        errorAlert.empty();
                        errorAlert.removeClass('d-none');
                        errorAlert.append("Gambar Asset Yang Dimasukkan Tidak Sesuai");
                    }
                    if (key == "id_checkbox") {
                        console.log($('#alert-list-asset'));
                        $('#alert-list-asset').removeClass('d-none');
                    }
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                    if (key == "file_asset_service") {
                        $('#preview-file-image-error').html(errors[key][0]);
                        $('#preview-file-image-error-update').html(errors[key][0]);
                    }

                }
            });

            table3.DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.pemutihan-asset.datatable.asset') }}",
                    data: function(d) {
                        d.id_pemutihan = "{{ $pemutihan_asset->id }}"
                        d.jenis = $('.jenispicker').val();
                        d.status_kondisi = $('.kondisipicker').val();
                    }
                },
                columns: [{
                        name: 'id',
                        data: 'id',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'kode_asset'
                    },
                    {
                        name: 'deskripsi',
                        data: 'deskripsi'
                    },
                    {
                        name: 'jenis_asset',
                        data: 'jenis_asset',
                    },
                    {
                        name: 'is_inventaris',
                        data: 'is_inventaris',
                        render: function(type) {
                            return type == 1 ? 'Inventaris' : 'Asset';
                        }
                    },
                    {
                        name: 'lokasi_asset',
                        data: 'lokasi_asset'
                    },
                    {
                        name: 'kondisi_asset',
                        data: 'kondisi_asset'
                    },

                ],
                columnDefs: [
                    //Custom template data
                ],
            });
        });
        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('#file_asset_service').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text').text(file.name);
        });
        const showPemutihanAsset = (button) => {
            const url = $(button).data('url_detail');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const data = response.data;
                    const modal = $('.modalPreviewAsset');
                    if (response.success) {
                        if (data.image.length > 0) {
                            $('#imgPreviewAsset').attr('src', data.image[0].link);
                        } else {
                            $('#imgPreviewAsset').attr('src',
                                'https://via.placeholder.com/400x250?text=Preview Image');
                        }
                        modal.modal('show');
                    }
                },
            })
        }



        const editListAsset = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditInventarisData');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    modal.on('shown.bs.modal', function(e) {
                        $('#status_pemutihan option[value="' + response.data
                            .status + '"]').attr('selected', 'selected');
                        table3.DataTable().ajax.reload();
                    })
                    modal.on('hidden.bs.modal', function() {
                        table3.DataTable().ajax.reload();
                        form[0].reset();
                    })
                    modal.modal('show');
                }
            })
        }
        const filterTableService = () => {
            table3.DataTable().ajax.reload();
        }

        const generateKategoriSelect2Create = (idElement) => {
            $('#' + idElement).select2({
                width: '100%',
                placeholder: 'Pilih Jenis',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }

        $('.modalEditInventarisData').on('shown.bs.modal', function() {
            setTimeout(() => {
                generateKategoriSelect2Create('groupAssetCreate');
            }, 2000);
        });
    </script>
@endsection
@section('main-content')
    <form action="{{ route('admin.pemutihan-asset.update', $pemutihan_asset->id) }}"
        class="kt-form kt-form--fit kt-form--label-right form-submit" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4 col-12">
                <h5 class="mb-3"><strong class="text-primary">Penghapusan Asset</strong> <span class="text-gray"> -
                        Ubah Data Penghapusan Asset</span></h5>
                <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
                    <table id="tableProperti" class="table table-striped">
                        <tr>
                            <td width="40%">Tanggal Penghapusan Asset</td>
                            <td>
                                <input type="text" class="form-control datepickerCreate"
                                    value="{{ $pemutihan_asset->tanggal }}" readonly name="tanggal">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">No Berita Acara</td>
                            <td>
                                <input type="text" class="form-control" value="{{ $pemutihan_asset->no_memo }}"
                                    name="no_berita_acara">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Nama Penghapusan Asset</td>
                            <td>
                                <input type="text" class="form-control" value="{{ $pemutihan_asset->nama_pemutihan }}"
                                    name="nama_pemutihan">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Keterangan Umum</td>
                            <td>
                                <textarea cols="30" rows="10" class="form-control" name="keterangan_pemutihan">{{ $pemutihan_asset->keterangan }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Status Penghapusan Asset</td>
                            <td>
                                <select name="status_pemutihan" class="form-control">
                                    <option value="Draft" {{ $pemutihan_asset->status == 'Draft' ?? 'selected' }}>Draft
                                    </option>
                                    <option value="Publish" {{ $pemutihan_asset->status == 'Publish' ?? 'selected' }}>
                                        Publish
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">File Berita Acara</td>
                            <td>
                                <a href="{{ route('admin.pemutihan-asset.store.detail.download') . '?filename=' . $pemutihan_asset->file_bast }}"
                                    download class="btn btn-primary shadow-custom btn-sm"><i class="fa fa-download"></i>
                                    Unduh</a>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Ganti File Berita Acara</td>
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span id="preview-file-image-text">No File Choosen</span> <br>
                                        <span id="preview-file-image-error" class="text-danger"></span>
                                    </div>
                                    <label for="file_asset_service" class="btn btn-primary">
                                        Upload
                                        <input type="file" id="file_asset_service" accept=".pdf,.docx,.doc"
                                            class="d-none" name="file_berita_acara">
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Dibuat Oleh</td>
                            <td><strong>{{ $pemutihan_asset->created_by_name }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-8 col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p>Kesalahan terdeteksi:</p>
                        <ol>
                            @foreach ($errors->all() as $index => $item)
                                <li> {{ ucFirst($item) }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                <div class="alert alert-danger d-none" id="alert-error"></div>

                <div class="kt-portlet shadow-custom">
                    <div class="kt-portlet__head px-4">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Daftar Asset Dalam Penghapusan <span
                                    class="text-primary"><b>({{ $pemutihan_asset->detail_pemutihan_asset->count() }}
                                        Asset)</b></span>
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-wrapper">
                                <div class="kt-portlet__head-actions">
                                    <button type="button" onclick="editListAsset(this)"
                                        data-url_edit="{{ route('admin.pemutihan-asset.edit.listing-asset', $pemutihan_asset->id) }}"
                                        data-url_update="{{ route('admin.pemutihan-asset.edit.listing-asset.update', $pemutihan_asset->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Ubah </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <label for="">List Asset Yang Akan Penghapusan Asset</label>
                        <div class="table-responsive">
                            <table class="table table-striped dt_table" id="datatableExample">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>#</th>
                                        <th>Kode Asset</th>
                                        <th>Deskripsi Asset</th>
                                        <th>Jenis Asset</th>
                                        <th>Tipe</th>
                                        <th>Lokasi Asset</th>
                                        <th>Keterangan Penghapusan Asset</th>
                                        <th>Ubah Foto Asset</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pemutihan_asset->detail_pemutihan_asset as $index => $item)
                                        @php
                                            $json_asset = json_decode($item->json_asset);
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><button type="button" onclick="showPemutihanAsset(this)"
                                                    data-url_detail="{{ route('admin.pemutihan-asset.edit.listing-asset.get-image', $item->id) }}"
                                                    class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button></td>
                                            <td>{{ $json_asset->kode_asset ?? 'Tidak Ada' }}</td>
                                            <td>{{ $json_asset->deskripsi ?? 'Tidak Ada' }}</td>
                                            <td>{{ empty($json_asset->kategori_asset->nama_kategori) ? 'Tidak Ada' : $json_asset->kategori_asset->nama_kategori }}
                                            </td>
                                            <td>{{ ucWords($json_asset->is_inventaris) == 1 ? 'Inventaris' : 'Asset' }}
                                            </td>

                                            <td>{{ empty($json_asset->lokasi->nama_lokasi) ? 'Tidak Ada' : $json_asset->lokasi->nama_lokasi }}
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="hidden" name="id_asset[]" value="{{ $item->id }}">
                                                    <textarea cols="15" rows="5" class="form-control" name="keterangan_pemutihan_asset[]">{{ $item->keterangan_pemutihan }}</textarea>
                                                </div>
                                            </td>
                                            <td width="220px">
                                                <div class="form-group">
                                                    <input type="file" name="gambar_asset[]"
                                                        accept=".jpeg,.png,.jpg,.gif,.svg" id="gambar_asset"
                                                        class="form-control">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right mt-2">
                            <a href="{{ route('admin.pemutihan-asset.index') }}" class="btn btn-secondary">Batalkan</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('pages.admin.pemutihan-asset.components.modal._modal_edit')
    @include('pages.admin.pemutihan-asset.components.modal._modal_preview')
@endsection
