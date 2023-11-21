@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>
        .datepicker-months .table-condensed thead {
            display: none;
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
        var table = $('#datatableExample');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                searching: false,
                // ordering: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.report.depresiasi.datatable') }}",
                    data: function(d) {
                        d.bulan_depresiasi = $('.monthpicker').val();
                        d.tahun_depresiasi = $('.yearpicker').val();
                        d.group_kategori_asset = $('#groupAssetCreate').val();
                        d.kategori_asset = $('#kategoriAssetCreate').val();
                        d.keyword = $('#searchDepresiasi').val();
                        d.is_it = $('#is_it').val();
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
                        data: "group",
                        name: 'group'
                    },
                    {
                        name: 'nama_kategori',
                        data: 'nama_kategori'
                    },
                    {
                        name: 'kode_asset',
                        data: 'kode_asset'
                    },
                    {
                        name: 'deskripsi',
                        data: 'deskripsi'
                    },
                    {
                        name: 'tanggal_depresiasi',
                        data: 'tanggal_depresiasi'
                    },
                    {
                        name: 'nilai_depresiasi',
                        data: 'nilai_depresiasi'
                    },
                    {
                        name: 'nilai_buku_awal',
                        data: 'nilai_buku_awal'
                    },
                    {
                        name: 'nilai_buku_akhir',
                        data: 'nilai_buku_akhir'
                    },
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [6, 7, 8],
                        render: function(data, type, full, meta) {
                            return formatNumber(data);
                        }
                    }
                ],
            });
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    //
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //
            });

            generateMonthPicker();
            generateYearPicker();
            $("#searchDepresiasi").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableAsset();
            });
        });

        $('.modalFilterAsset').on('shown.bs.modal', function() {
            setTimeout(() => {
                generateGroupSelect2('groupAssetCreate');
            }, 2000);
        });

        $('#groupAssetCreate').on('change', function() {
            generateKategoriSelect2Create('kategoriAssetCreate', $(this).val());
        });

        const filterTableAsset = () => {
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const bulan_depresiasi = $('.monthpicker').val(null);
            const tahun_depresiasi = $('.yearpicker').val(null);
            const group_kategori_asset = $('#groupAssetCreate').val(null);
            const kategori_asset = $('#kategoriAssetCreate').val(null);
            const keyword = $('#searchDepresiasi').val(null);
            const is_it = $('#is_it').val(null);
            table.DataTable().ajax.reload();
        }


        const generateMonthPicker = () => {
            $('.monthpicker').datepicker({
                format: "mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true,
            })
        }

        const generateYearPicker = () => {
            $('.yearpicker').datepicker({
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years',
                clearBtn: true,
                autoclose: true,
            });
        }

        const generateGroupSelect2 = (idElement) => {
            $('#' + idElement).select2({
                width: '100%',
                placeholder: 'Pilih Kelompok',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.group-kategori-asset.get-data-select2') }}',
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

        const generateKategoriSelect2Create = (idElement, idGroup) => {
            $('#' + idElement).removeAttr('disabled');
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
                            id_group_kategori_asset: idGroup,
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

        const exportDepresiasi = () => {
            let year = $('#yearExport').val();
            let url = "{{ route('admin.report.depresiasi.download-export') }}" + '?year=' + year;
            window.open(url, '_blank');
        }
    </script>
@endsection
@section('main-content')
    @if ($user->role == 'staff_asset' || $user->role == 'manager_asset')
        <input type="hidden" name="" id="is_it" value="0">
    @elseif($user->role == 'manager_it' || $user->role == 'staff_it')
        <input type="hidden" name="" id="is_it" value="1">
    @endif
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.report.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Depresiasi Aset
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalFilterAsset')"
                                    class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter </button>
                                <button onclick="resetFilterData()" id="resetFilter"
                                    class="btn btn-sm d-none btn-danger shadow-custom ml-2" type="button"><i
                                        class="fas fa-sync"></i>Reset</button>
                                <button onclick="openModalByClass('modalExport')"
                                    class="btn btn-success ml-1 shadow-custom btn-sm" type="button"><i
                                        class="fas fa-print"></i>Export Excel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="d-flex align-items-center">
                        <div class="input-group mr-3" style="width: 250px;">
                            <input type="text" id="searchDepresiasi" onkeyup="filterTableAsset()" class="form-control form-control-sm"
                                placeholder="Search for...">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButton"
                                    type="button"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Kelompok Aset</th>
                                    <th>Jenis Aset</th>
                                    <th>Kode Aset</th>
                                    <th>Deskripsi Aset</th>
                                    <th>Tanggal Depresiasi</th>
                                    <th>Nilai Depresiasi</th>
                                    <th>Nilai Awal</th>
                                    <th>Nilai Akhir</th>
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
    @include('pages.admin.report.depresiasi._modal_filter')
    @include('pages.admin.report.depresiasi._modal_export')
@endsection
