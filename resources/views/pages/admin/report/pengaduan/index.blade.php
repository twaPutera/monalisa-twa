@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>
        div.dataTables_wrapper {
            width: 200% !important;
        }
    </style>
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        var table = $('#datatableLogService');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                orderable: true,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.report.history-pengaduan.datatable') }}",
                    data: function(d) {
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
                        d.status_pengaduan = $('#statusPengaduan').val();
                        d.id_asset_data = $('#assetDataService').val();
                        d.id_lokasi = $('#lokasiAssetCreateService').val();
                        d.id_kategori_asset = $('#listKategoriAssetLocation').val();
                        d.keyword = $('#searchServices').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'DT_RowIndex'
                    },
                    {
                        name: 'tanggal_keluhan',
                        data: 'tanggal_keluhan'
                    },
                    {
                        name: 'kode_pengaduan',
                        data: 'kode_pengaduan'
                    },
                    {
                        name: 'log_terakhir',
                        data: 'log_terakhir'
                    },
                    {
                        name: 'nama_asset',
                        data: 'nama_asset'
                    },
                    {
                        name: 'lokasi_asset',
                        data: 'lokasi_asset'
                    },
                    {
                        name: 'prioritas_pengaduan',
                        data: 'prioritas_pengaduan'
                    },
                    {
                        name: 'catatan_pengaduan',
                        data: 'catatan_pengaduan'
                    },
                    {
                        name: 'status_pengaduan',
                        data: 'status_pengaduan',
                    },
                    {
                        name: 'gambar_pengaduan',
                        data: 'gambar_pengaduan',
                        orderable: false
                    },
                    {
                        name: 'created_by_name',
                        data: 'created_by_name',
                        orderable: false
                    },
                    {
                        name: 'message_log',
                        data: 'message_log',
                        orderable: false
                    },
                    {
                        name: 'dilakukan_oleh',
                        data: 'dilakukan_oleh',
                        orderable: false
                    },

                ],
                columnDefs: [{
                        targets: 8,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "dilaporkan") {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Laporan Masuk</span>`;
                            } else if (data == "diproses") {
                                element +=
                                    `<span class="kt-badge kt-badge--info kt-badge--inline">Diproses</span>`;
                            } else if (data == "selesai") {
                                element +=
                                    `<span class="kt-badge kt-badge--success kt-badge--inline">Selesai</span>`;
                            }
                            return element;
                        },
                    },
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: [3],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateTimeIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == 10) {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">High</span>`;
                            } else if (data == 5) {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Medium</span>`;
                            } else if (data == 1) {
                                element +=
                                    `<span class="kt-badge kt-badge--info kt-badge--inline">Low</span>`;
                            } else {
                                element +=
                                    `<span class="kt-badge kt-badge--dark kt-badge--inline">Tidak Ada</span>`;
                            }
                            return element;
                        },
                    }
                ],
            });
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
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

            $('#lokasiAssetCreateService').select2({
                width: '100%',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
            })
            $('#listKategoriAssetLocation').select2({
                width: '100%',
                placeholder: 'Pilih Jenis Asset',
                padding: '10px',
                allowClear: true,
            })
            $('#assetDataService').select2({
                width: '100%',
                placeholder: 'Pilih Asset',
                padding: '10px',
                allowClear: true,
            })
            generateLocationServiceSelect();
            generateLocationAsset();
            generateAssetServiceSelect();
            exportData();
            $("#searchServices").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableService();
            });
        });

        const exportData = () => {
            let awal = $('.datepickerAwal').val();
            let akhir = $('.datepickerAkhir').val();
            let id_asset_data = $('#assetDataService').val();
            let status_pengaduan = $('#statusPengaduan').val();
            let id_lokasi = $('#lokasiAssetCreateService').val();
            let id_kategori_asset = $('#listKategoriAssetLocation').val();
            $('#tgl_awal_export').val(awal);
            $('#status_pengaduan_export').val(status_pengaduan);
            $('#tgl_akhir_export').val(akhir);
            $('#id_asset_data_export').val(id_asset_data);
            $('#id_lokasi_export').val(id_lokasi);
            $('#id_kategori_asset_export').val(id_kategori_asset);

        }

        const detail = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    const modal = $('.modalDetailPengaduanData');
                    const detail = modal.find('.modalDetailBodyData');
                    detail.empty();
                    detail.append(response);
                    modal.modal('show');
                }
            })
        }
        const generateAssetServiceSelect = () => {
            $.ajax({
                url: "{{ route('admin.listing-asset.get-all-data-asset-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('#assetDataService');
                        select.empty();
                        select.append(`<option value="">Pilih Asset</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }
        const showKeluhanImage = (button) => {
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
        const generateLocationServiceSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('#lokasiAssetCreateService');
                        select.empty();
                        select.append(`<option value="">Pilih Lokasi</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const generateLocationAsset = () => {
            $.ajax({
                url: "{{ route('admin.setting.kategori-asset.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('#listKategoriAssetLocation');
                        select.empty();
                        select.append(`<option value="">Pilih Jenis Asset</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const filterTableService = () => {
            exportData();
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const awal = $('.datepickerAwal').val(null);
            const akhir = $('.datepickerAkhir').val(null);
            const status_pengaduan = $('#statusPengaduan').val(null);
            const id_asset_data = $('#assetDataService').val(null);
            const id_lokasi = $('#lokasiAssetCreateService').val(null);
            const id_kategori_asset = $('#listKategoriAssetLocation').val(null);
            const keyword = $('#searchServices').val(null);
            table.DataTable().ajax.reload();
        }

        $('.datepickerAwal').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.datepickerAkhir').datepicker({
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
            @include('pages.admin.report.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            History Pengaduan
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <form action="{{ route('admin.report.history-pengaduan.download-export') }}"
                                    method="get">
                                    <div class="d-md-flex d-block align-items-center mt-2 mb-2">
                                        <input type="hidden" name="id_lokasi" id="id_lokasi_export">
                                        <input type="hidden" name="status_pengaduan" id="status_pengaduan_export">
                                        <input type="hidden" name="id_kategori_asset" id="id_kategori_asset_export">
                                        <input type="hidden" name="tgl_awal" id="tgl_awal_export">
                                        <input type="hidden" name="tgl_akhir" id="tgl_akhir_export">
                                        <input type="hidden" name="id_asset_data" id="id_asset_data_export">
                                        <button type="button" onclick="openModalByClass('modalFilterAsset')"
                                            class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter </button>
                                        <button onclick="resetFilterData()" id="resetFilter"
                                            class="btn btn-sm d-none btn-danger shadow-custom ml-2" type="button"><i
                                                class="fas fa-sync"></i>Reset</button>
                                        <button class="btn btn-success shadow-custom btn-sm ml-2" type="submit"><i
                                                class="fas fa-print"></i>
                                            Export Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="input-group mr-3" style="width: 250px;">
                                <input type="text" id="searchServices" onkeyup="filterTableService()" class="form-control form-control-sm"
                                    placeholder="Search for...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-icon" onclick="filterTableService()"
                                        id="searchButton" type="button"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="datatableLogService">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Tanggal Pengaduan</th>
                                    <th>Kode Pengaduan</th>
                                    <th>Log Terakhir</th>
                                    <th>Nama Asset</th>
                                    <th>Lokasi Asset</th>
                                    <th>Prioritas Pengaduan</th>
                                    <th>Catatan Pengaduan</th>
                                    <th>Status Pengaduan</th>
                                    <th>Gambar Pengaduan</th>
                                    <th>Dilaporkan Oleh</th>
                                    <th>Aktifitas</th>
                                    <th>Dilakukan Oleh</th>
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
    @include('pages.admin.report.pengaduan.components.modal._modal_preview')
    @include('pages.admin.report.pengaduan.components.modal._modal_detail_keluhan')
    @include('pages.admin.report.pengaduan.components.modal._modal_filter_keluhan')
@endsection
