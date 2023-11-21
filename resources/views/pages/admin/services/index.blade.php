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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.0/chart.js"
        integrity="sha512-ohOeYvGoLlCxYkfMoPBKJh/wp4Oe76rEJDWOmQq1LLrJD6yCBSPVmhhXuZYvuxdYR3PiozsUf+TZZ6yhVBGYAQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('custom_js')
    <script>
        var table = $('#datatableLogService');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                paging: true,
                info: true,
                ajax: {
                    url: "{{ route('admin.listing-asset.service-asset.datatable') }}",
                    data: function(d) {
                        d.month = $('.monthpicker').val();
                        d.year = $('.yearpicker').val();
                        d.status_service = $("input[name='status_services']:checked").val();
                        d.id_lokasi = $('#lokasiFilter').val();
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
                        data: "action",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        name: 'tanggal_mulai',
                        data: 'tanggal_mulai'
                    },
                    {
                        name: 'kode_services',
                        data: 'kode_services'
                    },
                    {
                        name: 'asset_deskripsi',
                        data: 'asset_deskripsi'
                    },
                    {
                        name: 'is_inventaris',
                        data: 'is_inventaris',
                        render: function(type) {
                            return type == 1 ? "Inventaris" : "Asset";
                        }
                    },
                    {
                        data: 'nama_group',
                        name: 'nama_group'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori'
                    },
                    {
                        name: 'status_service',
                        data: 'status_service'
                    },
                    {
                        name: 'tanggal_selesai',
                        data: 'tanggal_selesai'
                    },
                ],
                columnDefs: [{
                        targets: [2, 9],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: 8,
                        render: function(data, type, full, meta) {
                            let element = "";
                            if (data == "on progress") {
                                element +=
                                    `<span class="kt-badge kt-badge--primary kt-badge--inline">Proses</span>`;
                            } else if (data == "backlog") {
                                element +=
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline">Tertunda</span>`;
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
                    showToastSuccess('Sukses', data.message);
                    $('#preview-file-error').html('');
                    table.DataTable().ajax.reload();
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                    if (key == "file_asset_service") {
                        $('#preview-file-image-error').html(errors[key][0]);
                        $('#preview-file-image-error-update').html(errors[key][0]);
                    }
                }
            });
            $('#lokasiFilter').select2({
                width: '150px',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
            })
            generateKategoriServiceSelect();
            generateLocationServiceSelect();
            generateAssetServiceSelect();
            generateMonthPicker();
            generateYearPicker();
            selectServiceDate('root');
            selectServiceDateUpdate('baru');
            $("#searchServices").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableService();
            });
        });
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
                autoclose: true,
            });
        }

        @if (isset(request()->service_id))
            setTimeout(() => {
                $('button[data-id_detail_service="{{ request()->service_id }}"]').click();
            }, 1000);
        @endif

        const filterTableService = () => {
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }
    </script>
    <script>
        const ctxChartServices = document.getElementById('chartServices');
        const chartServices = new Chart(ctxChartServices, {
            type: 'pie',
            data: {
                labels: ['PROSES', 'TERTUNDA', 'SELESAI'],
                datasets: [{
                    label: '# of Votes',
                    data: [50, 20, 30],
                    backgroundColor: [
                        '#0067D4',
                        '#F03E3E',
                        '#71C160',
                    ],
                    borderWidth: 1
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 40,
                        right: 40,
                        top: 40,
                        bottom: 40
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: 'rgba(118, 118, 118, 1)',
                        font: {
                            size: '10',
                        },
                        formatter: function(value, context) {
                            return context.chart.data.labels[context.dataIndex] + `\n (${value.toFixed(2)}%)`;
                        }
                    }
                }
            }
        });
        const changeChartData = (data) => {
            chartServices.data.datasets[0].data = data;
            chartServices.update();
        }

        const getDataChart = () => {
            $.ajax({
                url: "{{ route('admin.services.get-data-chart') }}",
                method: "GET",
                data: {
                    month: $('.monthpicker').val(),
                    year: $('.yearpicker').val(),
                },
                success: function(response) {
                    changeChartData(response.data);
                }
            })
        }

        $(document).ready(function() {
            getDataChart();
        })

        const filterTime = () => {
            getDataChart();
            filterTableService();
        }
    </script>
    <script>
        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.datepickerCreateSelesai').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('#file_asset_service').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text').text(file.name);
        });

        $('#file_asset_service_update').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text-update').text(file.name);
        });

        const editService = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            const id_asset = $(button).data('id_asset');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditAssetService');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=tanggal_mulai_service]').val(response.data.tanggal_mulai);
                    form.find('textarea[name=permasalahan]').val(response.data.detail_service.permasalahan);
                    form.find('textarea[name=tindakan]').val(response.data.detail_service.tindakan);
                    form.find('textarea[name=catatan]').val(response.data.detail_service.catatan);
                    modal.on('shown.bs.modal', function(e) {
                        $('#kategoriServiceUpdate option[value="' + response.data
                            .id_kategori_service + '"]').attr('selected', 'selected');
                        $('#kategoriServiceUpdate').select2({
                            width: '100%',
                            placeholder: 'Pilih Kategori Service',
                            allowClear: true,
                            parent: $(this)
                        });

                        $('#lokasiAssetUpdateService option[value="' + response.data
                            .detail_service.id_lokasi + '"]').attr('selected', 'selected');
                        $('#lokasiAssetUpdateService').select2({
                            width: '100%',
                            placeholder: 'Pilih Lokasi',
                            allowClear: true,
                            parent: $(this)
                        });

                        $('#listAssetLocationUpdate option[value="' + response.data
                            .detail_service.id_asset_data + '"]').attr('selected', 'selected');
                        $('#listAssetLocationUpdate').select2({
                            width: '100%',
                            placeholder: 'Pilih Lokasi',
                            allowClear: true,
                            parent: $(this)
                        });

                    })
                    modal.modal('show');
                }
            })
        }

        const editStatusService = (button) => {
            const url_edit_status = $(button).data('url_edit_status');
            const url_update_status = $(button).data('url_update_status');
            const id_asset = $(button).data('id_asset');
            $.ajax({
                url: url_edit_status,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditStatusAssetService');
                    const form = modal.find('form');
                    form.trigger('reset');
                    form.attr('action', url_update_status);
                    form.find('input[name=tanggal_selesai_service]').val(response.data.tanggal_selesai);
                    modal.on('shown.bs.modal', function(e) {
                        if (response.data.status_service === "on progress") {
                            var status_service = "onprogress";
                        } else {
                            var status_service = response.data.status_service;
                        }
                        $('#status_service option[value="' + status_service + '"]')
                            .prop('selected', true);

                        $('#service_kondisi option[value="' + response.data.status_kondisi + '"]')
                            .prop('selected', true);
                    })
                    modal.modal('show');
                }
            })
        }

        const generateKategoriServiceSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.kategori-service.get-data-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectGroupKategoriService');
                        select.empty();
                        select.append(`<option value="">Pilih Kategori Service</option>`);
                        response.data.forEach((item) => {
                            select.append(
                                `<option value="${item.id}">${item.text}</option>`);
                        });
                    }
                }
            })
        }

        const selectServiceDate = (v) => {
            const tanggalBaru = $('#tanggalBaru');
            const tanggalPerencanaan = $('#tanggalPerencanaan');
            if (v == "baru") {
                tanggalBaru.removeClass('d-none');
                tanggalPerencanaan.addClass('d-none');
            } else if (v == "perencanaan") {
                tanggalPerencanaan.removeClass('d-none');
                tanggalBaru.addClass('d-none');
            } else {
                tanggalBaru.addClass('d-none');
                tanggalPerencanaan.addClass('d-none');
            }
        }
        const selectServiceDateUpdate = (v) => {
            const tanggalBaru = $('.tanggalBaru');
            const tanggalPerencanaan = $('.tanggalPerencanaan');
            if (v == "baru") {
                tanggalBaru.removeClass('d-none');
                tanggalPerencanaan.addClass('d-none');
            } else if (v == "perencanaan") {
                tanggalPerencanaan.removeClass('d-none');
                tanggalBaru.addClass('d-none');
            } else {
                tanggalBaru.addClass('d-none');
                tanggalPerencanaan.addClass('d-none');
            }
        }
        const generateLocationServiceSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectLocationService');
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
        const detailService = (button) => {
            const url_detail = $(button).data('url_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    const modal = $('.modalDetailInventarisData');
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
                        const select = $('.selectAssetService');
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

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const month = $('.monthpicker').val(null);
            const year = $('.yearpicker').val((new Date).getFullYear());
            const status_service = $("input[name='status_services']:checked").val(null);
            const id_lokasi = $('#lokasiFilter').val(null);
            const keyword = $('#searchServices').val(null);
            table.DataTable().ajax.reload();
        }
    </script>
    @include('pages.admin.services.components.js._script_modal_create')
@endsection
@section('main-content')
    <input type="hidden" value="" id="lokasiParentId">
    <div class="row">
        <div class="col-md-3 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Summary Service
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body p-0">
                    <canvas id="chartServices" height="200px"></canvas>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Asset sedang diservice</h6>
                    <h6 class="text-primary mb-0"><strong>{{ $data['totalService'] }}</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Lokasi terbanyak</h6>
                    <h6 class="text-primary mb-0"><strong>{{ $data['namaLokasi'] }} ({{ $data['totalLokasi'] }})</strong>
                    </h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Sedang di proses</h6>
                    <h6 class="text-primary mb-0"><strong>{{ $data['onProgress'] }}</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Selesai di kerjakan</h6>
                    <h6 class="text-primary mb-0"><strong>{{ $data['selesai'] }}</strong></h6>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <h6 class="mb-0">Sedang tertunda</h6>
                    <h6 class="text-primary mb-0"><strong>{{ $data['backlog'] }}</strong></h6>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="input-group mr-3" style="width: 250px;">
                        <input type="text" id="searchServices" onkeyup="filterTableService()"
                            class="form-control form-control-sm" placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" onclick="filterTableService()" id="searchButton"
                                type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <select name="" onchange="filterTime()"
                        class="filterLokasi selectLocationService form-control mr-2" style="width: 150px;"
                        id="lokasiFilter">

                    </select>
                    <input type="text" onchange="filterTime()" value="{{ date('Y') }}" readonly
                        class="form-control yearpicker mx-2" style="width: 150px;" placeholder="Tahun">
                    <input type="text" onchange="filterTime()" readonly class="form-control monthpicker mr-2"
                        style="width: 150px;" placeholder="Bulan">
                    <button onclick="resetFilterData()" id="resetFilter"
                        class="btn btn-sm d-none btn-danger text-white shadow-custom ml-2 mr-2" type="button"><i
                            class="fas fa-sync text-white"></i>Reset</button>
                    <button onclick="openModalByClass('modalCreateAssetService')"
                        class="btn btn-primary shadow-custom btn-sm" type="button"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><strong>Data Service</strong></h5>
                <div class="kt-radio-inline">
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" onchange="filterTableService()" checked="checked" value=""
                            name="status_services"> Semua Services
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" onchange="filterTableService()" name="status_services" value="on progress">
                        Proses
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" onchange="filterTableService()" name="status_services" value="backlog">
                        Tertunda
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--bold kt-radio--brand">
                        <input type="radio" onchange="filterTableService()" name="status_services" value="selesai">
                        Selesai
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="colTable">
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped mb-0" id="datatableLogService">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="100px">#</th>
                                    <th>Tgl. Mulai</th>
                                    <th>Kode Services</th>
                                    <th>Deskripsi Asset</th>
                                    <th>Tipe</th>
                                    <th>Kelompok</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Tgl. Selesai</th>
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
    @include('pages.admin.services.components.modal._modal_create_service')
    @include('pages.admin.services.components.modal._modal_edit_service')
    @include('pages.admin.services.components.modal._modal_edit_status_service')
    @include('pages.admin.services.components.modal._modal_detail_service')
@endsection
