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
        $(document).ready(function() {
            var table = $('#datatableExample');
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.listing-inventaris.datatable') }}",
                columns: [{
                        data: "DT_RowIndex",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'DT_RowIndex'
                    },
                    {
                        data: "action",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        data: 'kode_inventori'
                    },
                    {
                        data: 'nama_inventori'
                    },
                    {
                        name: 'kategori',
                        data: 'kategori'
                    },
                    {
                        name: 'sebelumnya',
                        data: 'sebelumnya'
                    },
                    {
                        name: 'saat_ini',
                        data: 'saat_ini'
                    },
                    {
                        data: 'deskripsi_inventori'
                    },

                ],
                columnDefs: [
                    //Custom template data
                ],
            });

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    generateKategoriSelect();
                    generateSatuanSelect();
                    generateInventarisSelect();
                    table.DataTable().ajax.reload();
                    showToastSuccess('Sukses', data.message);
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                if (!errors.success) {
                    showToastError('Gagal', errors.message);
                }
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                }
            });

            generateKategoriSelect();
            generateSatuanSelect();
            generateInventarisSelect();

            $('.modalCreateInventarisData').on('shown.bs.modal', function(e) {
                generateKategoriSelect();
                generateSatuanSelect();
                generateInventarisSelect();
                $('.selectKategoriData').select2({
                    width: '100%',
                    placeholder: 'Pilih Kategori Inventaris',
                    allowClear: true,
                    parent: $(this)
                });

                $('.selectInventarisData').select2({
                    width: '100%',
                    placeholder: 'Pilih Inventaris',
                    allowClear: true,
                    parent: $(this)
                });

                $('.selectSatuanData').select2({
                    width: '100%',
                    placeholder: 'Pilih Satuan Inventaris',
                    allowClear: true,
                    parent: $(this)
                });
            })

            $('.modalCreateInventarisData').on('hidden.bs.modal', function(e) {
                $(this).find('form').trigger('reset');
            });
        });

        const edit = (button) => {
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
                    form.find('input[name=kode_inventori]').val(response.data.kode_inventori);
                    form.find('input[name=nama_inventori]').val(response.data.nama_inventori);
                    form.find('input[name=stok_sebelumnya]').val(response.data.jumlah_sebelumnya);
                    form.find('input[name=stok_saat_ini]').val(response.data.jumlah_saat_ini);
                    form.find('textarea[name=deskripsi_inventori]').val(response.data.deskripsi_inventori);
                    modal.on('shown.bs.modal', function(e) {
                        // generateKategoriSelect();
                        $('#selectKategoriDataEdit option[value="' + response.data
                            .id_kategori_inventori + '"]').attr('selected', 'selected');
                        $('#selectKategoriDataEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Kategori Inventaris',
                            allowClear: true,
                            parent: $(this)
                        });
                        // $('#selectGroupEdit').select2('val', response.data.id_group_kategori_asset);
                        $('#selectSatuanDataEdit option[value="' + response.data
                                .id_satuan_inventori + '"]')
                            .prop('selected', 'selected');
                        $('#selectSatuanDataEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Satuan Inventaris',
                            allowClear: true,
                            parent: $(this)
                        });
                    })
                    modal.modal('show');
                }
            })
        }

        const stokEdit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditStokData');
                    const form = modal.find('form');
                    const table = modal.find('table');
                    form.attr('action', url_update);
                    table.find('strong[class=kode_inventori]').empty();
                    table.find('strong[class=nama_kategori]').empty();
                    table.find('strong[class=merk_inventaris]').empty();
                    table.find('strong[class=jumlah_sebelumnya]').empty();
                    table.find('strong[class=jumlah_saat_ini]').empty();
                    table.find('strong[class=deskripsi_inventaris]').empty();

                    table.find('strong[class=kode_inventori]').append(response.data.kode_inventori);
                    table.find('strong[class=nama_kategori]').append(response.data.kategori_inventori
                        .nama_kategori);
                    table.find('strong[class=merk_inventaris]').append(response.data.nama_inventori);
                    table.find('strong[class=jumlah_sebelumnya]').append(response.data.jumlah_sebelumnya +
                        " " + response.data.satuan_inventori.nama_satuan);
                    table.find('strong[class=jumlah_saat_ini]').append(response.data.jumlah_saat_ini +
                        " " + response.data.satuan_inventori.nama_satuan);
                    table.find('strong[class=deskripsi_inventaris]').append(response.data
                        .deskripsi_inventori);
                    modal.on('shown.bs.modal', function() {
                        generateMemorandumAndinSelect2();
                    }).modal('show');
                }
            })
        }

        const generateMemorandumAndinSelect2 = () => {
            $('#memorandumAndin').select2({
                width: '100%',
                placeholder: 'Pilih Memorandum',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('andin-api.find-data-memorandum') }}',
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
            }).on('change', function(e) {
                const data = $(this).select2('data')[0];
                $('#noMemoSurat').val(data.text);
            });
        }

        const generateInventarisSelect = () => {
            $.ajax({
                url: "{{ route('admin.listing-inventaris.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectInventarisData');
                        select.empty();
                        select.append(`<option value="">Pilih Inventaris</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const generateKategoriSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.kategori-inventori.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectKategoriData');
                        select.empty();
                        select.append(`<option value="">Pilih Kategori Inventaris</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const generateSatuanSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.satuan-inventori.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectSatuanData');
                        select.empty();
                        select.append(`<option value="">Pilih Satuan Inventaris</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const jenisPenambahan = (val) => {
            const modal = $('.modalCreateInventarisData');
            const form = modal.find('form');
            const form_inventaris = modal.find('#form-inventaris');
            if (val == "baru") {
                form.attr('action', "{{ route('admin.listing-inventaris.store') }}");
                form_inventaris.addClass('d-none');
            } else {
                form.attr('action', "{{ route('admin.listing-inventaris.store.update') }}");
                form_inventaris.removeClass('d-none');
                form_inventaris.find('select[name=id_inventaris]').on('change', function() {
                    $.ajax({
                        url: "{{ route('admin.listing-inventaris.get.one') }}",
                        type: 'GET',
                        data: {
                            id_inventori: this.value
                        },
                        dataType: 'json',
                        success: function(response) {
                            const form = modal.find('form');
                            form.find('input[name=kode_inventori]').val(response.data
                                .kode_inventori);
                            form.find('input[name=nama_inventori]').val(response.data
                                .nama_inventori);
                            form.find('textarea[name=deskripsi_inventori]').val(response.data
                                .deskripsi_inventori);
                            $('#selectKategoriDataCreate option[value="' + response.data
                                .id_kategori_inventori + '"]').attr('selected', 'selected');
                            $('#selectKategoriDataCreate').select2({
                                width: '100%',
                                placeholder: 'Pilih Kategori Inventaris',
                                allowClear: true,
                                parent: $(this)
                            });
                            // $('.selectGroupEdit').select2('val', response.data.id_group_kategori_asset);
                            $('#selectSatuanDataCreate option[value="' + response.data
                                .id_satuan_inventori + '"]').attr('selected', 'selected');
                            $('#selectSatuanDataCreate').select2({
                                width: '100%',
                                placeholder: 'Pilih Satuan Inventaris',
                                allowClear: true,
                                parent: $(this)
                            });
                        }
                    })
                });
            }
        }

        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.listing-inventaris.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Data Bahan Habis Pakai
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalCreateInventarisData')"
                                    class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="175px">#</th>
                                    <th>Jenis Bahan Habis Pakai</th>
                                    <th>Merk Bahan Habis Pakai</th>
                                    <th>Kategori Bahan Habis Pakai</th>
                                    <th>Jumlah Bahan Habis Pakai Sebelumnya</th>
                                    <th>Jumlah Bahan Habis Pakai Saat Ini</th>
                                    <th>Deskripsi Bahan Habis Pakai</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.listing-inventaris.listing-data.components.modal._modal_create')
    @include('pages.admin.listing-inventaris.listing-data.components.modal._modal_edit')
    @include('pages.admin.listing-inventaris.listing-data.components.modal._modal_edit_stok')
@endsection
