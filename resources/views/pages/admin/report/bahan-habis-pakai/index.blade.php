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
    </style>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        var table = $('#datatableLogBahanHabisPakai');
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
                    url: "{{ route('admin.report.history-bahan-habis-pakai.datatable') }}",
                    data: function(d) {
                        d.awal_permintaan = $('.datepickerAwalPermintaan').val();
                        d.akhir_permintaan = $('.datepickerAkhirPermintaan').val();
                        d.awal_pengambilan = $('.datepickerAwalPengambilan').val();
                        d.akhir_pengambilan = $('.datepickerAkhirPengambilan').val();
                        d.status_permintaan = $('#statusPermintaan').val();
                        // d.id_kategori_bahan_habis_pakai = $('#listKategoriAssetLocation').val();
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
                        name: 'tanggal_permintaan',
                        data: 'tanggal_permintaan'
                    },
                    {
                        name: 'kode_permintaan',
                        data: 'kode_permintaan'
                    },
                    {
                        name: 'kode_bahan_habis_pakai',
                        data: 'kode_bahan_habis_pakai',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        name: 'log_terakhir',
                        data: 'log_terakhir'
                    },
                    {
                        name: 'tanggal_pengambilan',
                        data: 'tanggal_pengambilan'
                    },
                    {
                        name: 'user_pengaju',
                        data: 'user_pengaju'
                    },
                    {
                        name: 'no_memo',
                        data: 'no_memo'
                    },
                    {
                        name: 'unit_kerja',
                        data: 'unit_kerja'
                    },
                    {
                        name: 'jabatan',
                        data: 'jabatan'
                    },
                    {
                        name: 'alasan',
                        data: 'alasan'
                    },
                    {
                        name: 'message',
                        data: 'message'
                    },
                    {
                        name: 'status',
                        data: 'status',
                        orderable: false,
                    },
                    {
                        name: 'created_by',
                        data: 'created_by',
                        orderable: false,
                    },

                ],
                columnDefs: [{
                        targets: [1, 4, 5],
                        render: function(data, type, full, meta) {
                            return data != 'Tidak Ada' ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: 12,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "pending") {
                                element +=
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline">Pending</span>`;
                            } else if (data == "diproses") {
                                element +=
                                    `<span class="kt-badge kt-badge--primary kt-badge--inline">Diproses</span>`;

                            } else if (data == "ditolak") {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">Ditolak</span>`;
                            } else if (data == "selesai") {
                                element +=
                                    `<span class="kt-badge kt-badge--success kt-badge--inline">Selesai</span>`;
                            }
                            return element;
                        },
                    }
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

            // $('#listKategoriAssetLocation').select2({
            //     width: '100%',
            //     placeholder: 'Pilih Kategori Bahan Habis Pakai',
            //     padding: '10px',
            //     allowClear: true,
            // })

            // generateKategoriBahanHabisPakai();
            exportData();
            $("#searchServices").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableService();
            });
        });
        const filterTableService = () => {
            exportData();
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const awal_permintaan = $('.datepickerAwalPermintaan').val(null);
            const akhir_permintaan = $('.datepickerAkhirPermintaan').val(null);
            const awal_pengambilan = $('.datepickerAwalPengambilan').val(null);
            const akhir_pengambilan = $('.datepickerAkhirPengambilan').val(null);
            const status_permintaan = $('#statusPermintaan').val(null);
            // const id_kategori_bahan_habis_pakai = $('#listKategoriAssetLocation').val(null);
            const keyword = $('#searchServices').val(null);
            table.DataTable().ajax.reload();
        }

        const exportData = () => {
            let awal_permintaan = $('.datepickerAwalPermintaan').val();
            let akhir_permintaan = $('.datepickerAkhirPermintaan').val();
            let awal_pengambilan = $('.datepickerAwalPengambilan').val();
            let akhir_pengambilan = $('.datepickerAkhirPengambilan').val();
            let status_permintaan = $('#statusPermintaan').val();
            // let id_kategori_bahan_habis_pakai = $('#listKategoriAssetLocation').val();
            $('#tgl_awal_permintaan_export').val(awal_permintaan);
            $('#tgl_akhir_permintaan_export').val(akhir_permintaan);
            $('#tgl_awal_pengambilan_export').val(awal_pengambilan);
            $('#tgl_akhir_pengambilan_export').val(akhir_pengambilan);
            $('#status_permintaan_export').val(status_permintaan);
            // $('#id_kategori_bahan_habis_pakai_export').val(id_kategori_bahan_habis_pakai);
        }

        // const generateKategoriBahanHabisPakai = () => {
        //     $.ajax({
        //         url: "{{ route('admin.setting.kategori-inventori.get-data-select2') }}",
        //         type: 'GET',
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.success) {
        //                 const select = $('#listKategoriAssetLocation');
        //                 select.empty();
        //                 select.append(`<option value="">Pilih Kategori Bahan Habis Pakai</option>`);
        //                 response.data.forEach((item) => {
        //                     select.append(
        //                         `<option value="${item.id}">${item.text}</option>`);
        //                 });
        //             }
        //         }
        //     })
        // }

        $('.datepickerAwalPermintaan').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.datepickerAkhirPermintaan').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.datepickerAwalPengambilan').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.datepickerAkhirPengambilan').datepicker({
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
                            History Bahan Habis Pakai
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <form action="{{ route('admin.report.history-bahan-habis-pakai.download-export') }}"
                                    method="get">
                                    <div class="d-flex align-items-center mt-2 mb-2">
                                        <input type="hidden" name="status_permintaan" id="status_permintaan_export">
                                        {{-- <input type="hidden" name="id_kategori_bahan_habis_pakai"
                                            id="id_kategori_bahan_habis_pakai_export"> --}}
                                        <input type="hidden" name="tgl_awal_permintaan" id="tgl_awal_permintaan_export">
                                        <input type="hidden" name="tgl_akhir_permintaan" id="tgl_akhir_permintaan_export">
                                        <input type="hidden" name="tgl_awal_pengambilan" id="tgl_awal_pengambilan_export">
                                        <input type="hidden" name="tgl_akhir_pengambilan"
                                            id="tgl_akhir_pengambilan_export">
                                        <button type="button" onclick="openModalByClass('modalFilterAsset')"
                                            class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter </button>
                                        <button onclick="resetFilterData()" id="resetFilter"
                                            class="btn btn-sm d-none btn-danger shadow-custom ml-2" type="button"><i
                                                class="fas fa-sync"></i>Reset</button>
                                        <button class="btn btn-success shadow-custom btn-sm ml-2" type="submit"
                                            type="button"><i class="fas fa-print"></i>
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
                                <input type="text" id="searchServices" onkeyup="filterTableService()"
                                    class="form-control form-control-sm" placeholder="Search for...">
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
                        <table class="table table-striped mb-0" id="datatableLogBahanHabisPakai">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Kode Permintaan</th>
                                    <th>Jenis Bahan Habis Pakai Dalam Permintaan Ini</th>
                                    <th>Log Terakhir</th>
                                    <th>Tanggal Pengambilan</th>
                                    <th>User Pengaju</th>
                                    <th>No Memo</th>
                                    <th>Unit Kerja</th>
                                    <th>Jabatan</th>
                                    <th>Alasan Permintaan</th>
                                    <th>Aktifitas</th>
                                    <th>Status</th>
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
    @include('pages.admin.report.bahan-habis-pakai.components.modal._modal_detail_bahan_habis_pakai')
    @include('pages.admin.report.bahan-habis-pakai.components.modal._modal_filter_bahan_habis_pakai')
@endsection
