@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <style>
        .text-summary-dashboard {
            font-size: 16px;
        }
    </style>
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom_js')
    <script>
        var datatableCriticalAduan = $('#datatableCriticalAduan');
        $(document).ready(function() {
            datatableCriticalAduan.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                // set limit item per page
                orderable: true,
                paging: false,
                info: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.keluhan.datatable') }}",
                    data: function(d) {
                        d.status_pengaduan = 'dilaporkan';
                        d.limit = 10;
                        d.global = true;
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
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
                        data: 'dashboard',
                        name: 'dashboard',
                        orderable: false
                    },
                    {
                        name: 'tanggal_pengaduan',
                        data: 'tanggal_pengaduan'
                    },
                    {
                        name: 'created_by_name',
                        data: 'created_by_name',
                        orderable: false
                    },
                    {
                        name: 'prioritas',
                        data: 'prioritas',
                        orderable: true
                    },
                    {
                        name: 'lokasi_asset',
                        data: 'lokasi_asset',
                        orderable: false
                    },

                ],
                order: [
                    [4, 'asc']
                ],
                columnDefs: [{
                    targets: 4,
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
                }],
            });
        });

        // var datatableAduanTerbaru = $('#datatableAduanTerbaru');
        // $(document).ready(function() {
        //     datatableAduanTerbaru.DataTable({
        //         responsive: true,
        //         searchDelay: 500,
        //         processing: true,
        //         searching: false,
        //         bLengthChange: false,
        //         // set limit item per page
        //         orderable: true,
        //         paging: false,
        //         info: false,
        //         scrollX: true,
        //         serverSide: true,
        //         ajax: {
        //             url: "{{ route('admin.keluhan.datatable') }}",
        //             data: function(d) {
        //                 d.status_pengaduan = 'dilaporkan';
        //                 d.limit = 10;
        //             }
        //         },
        //         columns: [{
        //                 data: "DT_RowIndex",
        //                 class: "text-center",
        //                 orderable: false,
        //                 searchable: false,
        //                 name: 'DT_RowIndex'
        //             },
        //             {
        //                 data: 'id',
        //                 name: 'id',
        //                 orderable: false
        //             },
        //             {
        //                 name: 'tanggal_pengaduan',
        //                 data: 'tanggal_pengaduan'
        //             },
        //             {
        //                 name: 'created_by_name',
        //                 data: 'created_by_name',
        //                 orderable: false
        //             },
        //             {
        //                 name: 'prioritas',
        //                 data: 'prioritas',
        //                 orderable: true
        //             },
        //             {
        //                 name: 'lokasi_asset',
        //                 data: 'lokasi_asset',
        //                 orderable: false
        //             },

        //         ],
        //         order: [
        //             [2, 'desc']
        //         ],
        //         columnDefs: [{
        //                 targets: 1,
        //                 render: function(data, type, full, meta) {
        //                     return '<button data-url_edit="{{ route('admin.keluhan.edit', ':id_edit') }}" data-url_update="{{ route('admin.keluhan.update', ':id_update') }}" onclick="editPengaduan(this)" class="btn btn-sm btn-primary btn-icon" title="View details">\
        //                                                                                                 <i class="la la-eye"></i>\
        //                                                                                             </button>'.replace(
        //                             ':id_edit',
        //                             data)
        //                         .replace(
        //                             ':id_update',
        //                             data);
        //                 },
        //             },
        //             {
        //                 targets: 4,
        //                 render: function(data, type, full, meta) {
        //                     let element = "";
        //                     if (data == 10) {
        //                         element +=
        //                             `<span class="kt-badge kt-badge--danger kt-badge--inline">High</span>`;
        //                     } else if (data == 5) {
        //                         element +=
        //                             `<span class="kt-badge kt-badge--warning kt-badge--inline">Medium</span>`;
        //                     } else if (data == 1) {
        //                         element +=
        //                             `<span class="kt-badge kt-badge--info kt-badge--inline">Low</span>`;
        //                     } else {
        //                         element +=
        //                             `<span class="kt-badge kt-badge--dark kt-badge--inline">Tidak Ada</span>`;
        //                     }
        //                     return element;
        //                 },
        //             }
        //         ],
        //     });
        // });

        const editPengaduan = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditKeluhanData');
                    const form = modal.find('form');
                    if (response.data.status_pengaduan === "dilaporkan") {
                        var status = '<div class="badge badge-warning">Laporan Masuk</div>';
                    } else if (response.data.status_pengaduan === "diproses") {
                        var status = '<div class="badge badge-info">Diproses</div>';
                    } else if (response.data.status_pengaduan === "selesai") {
                        var status = '<div class="badge badge-success">Selesai</div>';
                    }

                    if (response.data.prioritas == 10) {
                        var prioritas = 'High';
                    } else if (response.data.prioritas == 5) {
                        var prioritas = 'Medium';
                    } else if (response.data.prioritas == 1) {
                        var prioritas = 'Low';
                    } else {
                        var prioritas = 'Tidak Ada';
                    }
                    form.attr('action', url_update);

                    if (response.data.asset_data != null) {
                        if (response.data.asset_data != null) {
                            form.find('input[name=nama_asset]').val(response.data.asset_data.deskripsi);
                            form.find('input[name=lokasi_asset]').val(response.data.asset_data.lokasi
                                .nama_lokasi);
                            if (response.data.asset_data.kategori_asset != null) {
                                form.find('input[name=kelompok_asset]').val(response.data.asset_data
                                    .kategori_asset
                                    .group_kategori_asset.nama_group);
                                form.find('input[name=jenis_asset]').val(response.data.asset_data
                                    .kategori_asset
                                    .nama_kategori);
                            } else {
                                form.find('input[name=kelompok_asset]').val('-');
                                form.find('input[name=jenis_asset]').val('-');
                            }
                        } else {
                            form.find('input[name=nama_asset]').val("-");
                            form.find('input[name=kelompok_asset]').val("-");
                            form.find('input[name=jenis_asset]').val("-");
                            form.find('input[name=lokasi_asset]').val(response.data.lokasi
                                .nama_lokasi);
                        }
                    }

                    form.find('input[name=tanggal_pengaduan]').val(response.data.tanggal_pengaduan);
                    form.find('input[name=prioritas_pengaduan]').val(prioritas);
                    form.find('#status_laporan').empty();
                    form.find('#status_laporan').append(status);
                    form.find('textarea[name=catatan_pengaduan]').val(response.data.catatan_pengaduan);
                    form.find('input[name=diajukan_oleh]').val(response.data.created_by_name);
                    modal.modal('show');
                }
            })
        }
        $('#file_pendukung').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });
        const detailPengaduan = (button) => {
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
    </script>
    <script>
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
        var datatablePerencanaanServices = $('#datatablePerencanaanServices');
        $(document).ready(function() {
            datatablePerencanaanServices.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                // set limit item per page
                // pageLength: 3,
                orderable: true,
                paging: false,
                info: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.services.datatable-perencanaan-service') }}",
                    data: function(d) {
                        d.status = 'pending';
                        d.limit = 10;
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
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
                        name: 'dashboard',
                        data: 'dashboard',
                        orderable: false
                    },
                    {
                        name: 'tanggal_perencanaan',
                        data: 'tanggal_perencanaan'
                    },
                    {
                        name: 'asset_deskripsi',
                        data: 'asset_deskripsi',
                    },

                ],
                order: [
                    [2, 'desc']
                ],
                columnDefs: [
                    // Here
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
                    datatableCriticalAduan.DataTable().ajax.reload();
                    datatablePerencanaanServices.DataTable().ajax.reload();
                    datatableServicesOnProgress.DataTable().ajax.reload();
                    tableLogOpname.DataTable().ajax.reload();
                    tableAsetPengambangan.DataTable().ajax.reload();
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
        });

        const addServicesFromPerencanaan = (element) => {
            const url_show = $(element).data('url_show');
            $.ajax({
                url: url_show,
                data: {
                    relations: ['asset_data', 'log_asset_opaname']
                },
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#assetLokasiIdServices').val(data.asset_data.id_lokasi);
                        $('#idPerencanaanServices').val(data.id);
                        $('#assetIdServices').val(data.asset_data.id);
                        $('#assetNameServices').val(data.asset_data.deskripsi);
                        $('#tanggalServices').val(data.tanggal_perencanaan);
                        $('#permasalahanServices').val(data.keterangan);
                        $('#modalCreateAssetService').on('shown.bs.modal', function() {
                            generateSelect2KategoriService();
                        }).modal('show');
                    }
                },
            })
        }

        $('#file_asset_service').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text').text(file.name);
        });

        var datatableServicesOnProgress = $('#datatableServicesOnProgress');
        var tableLogOpname = $('#tableLogOpname');
        $(document).ready(function() {
            datatableServicesOnProgress.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.service-asset.datatable') }}",
                    data: function(d) {
                        d.status_service = 'on progress';
                        d.global = true;
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
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
                        data: "dashboard",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'dashboard'
                    },
                    {
                        name: 'kode_services',
                        data: 'kode_services'
                    },
                    {
                        name: 'tanggal_mulai',
                        data: 'tanggal_mulai'
                    },
                    {
                        name: 'asset_data.deskripsi',
                        data: 'asset_data.deskripsi'
                    },
                ],
                columnDefs: [{
                        targets: [3],
                        render: function(data, type, full, meta) {
                            return data != null ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    //Custom template data
                ],
                order: [
                    [3, 'desc']
                ],
            });

            tableLogOpname.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                orderable: true,
                serverSide: true,
                bLengthChange: false,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.log-opname.datatable') }}",
                    data: function(d) {
                        d.limit = 10;
                        d.global = true;
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
                    }
                },
                columns: [{
                        data: 'kode_asset',
                    },
                    {
                        data: 'deskripsi',
                    },
                    {
                        data: 'status_akhir',
                    },
                    {
                        data: 'kritikal',
                        name: 'kritikal',
                    },
                    {
                        data: 'keterangan',
                    },
                    {
                        data: 'id_asset_data',
                    },
                ],
                columnDefs: [{
                        targets: 3,
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
                                    `-`;
                            }
                            return element;
                        },
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            let element = "";
                            element +=
                                `<a href="{{ route('admin.listing-asset.detail', ':id') }}" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="View">
                                    <i class="la la-eye"></i>
                                </a>`;
                            element = element.replace(/:id/g, data);
                            return element;
                        },
                    }
                    //Custom template data
                ],
                order: [
                    [3, 'desc']
                ],
            });
        });

        const generateSelect2KategoriService = () => {
            $('#kategoriServiceCreate').select2({
                width: '100%',
                placeholder: 'Pilih Kategori Service',
                dropdownParent: $('.modal.show'),
                ajax: {
                    url: '{{ route('admin.setting.kategori-service.get-data-select2') }}',
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
    </script>
    <script>
        $(document).ready(function() {
            $('.datepickerCreateSelesai').datepicker({
                todayHighlight: true,
                width: '100%',
                format: 'yyyy-mm-dd',
                autoclose: true,
            });
            getSummaryData();
        });

        const getSummaryData = () => {
            $.ajax({
                url: '{{ route('admin.get-summary-dashboard') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'is_pemutihan': '0',
                    'is_draft': '0',
                    'global': true,
                    'awal': $('.datepickerAwal').val(),
                    'akhir': $('.datepickerAkhir').val()
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#totalAssetSummary').text(response.data.countAsset);
                        $('#lastUpdateAsset').text(formatDateIntoIndonesia(response.data.lastUpdateAsset));
                        $('#nilaiBeliAsset').text(formatNumberToMilion(response.data.nilaiAsset
                            .nilai_beli_asset));
                        $('#totalDepresiasiAsset').text(formatNumberToMilion(response.data.nilaiAsset
                            .nilai_depresiasi));
                        $('#totalValueAsset').text(formatNumberToMilion(response.data.nilaiAsset
                            .nilai_value_asset));
                        $('#keluhan-total').empty();
                        $('#keluhan-ditangani-total').empty();
                        $('#keluhan-belum-total').empty();
                        $('#keluhan-total').append(response.data.dataTotalPengaduan)
                        $('#keluhan-ditangani-total').append(response.data.dataSudahDitangani)
                        $('#keluhan-belum-total').append(response.data.dataBelumDitangani)
                        const dataSummaryAsset = response.data.dataSummaryChartAsset.map((item) => {
                            return {
                                name: item.name,
                                value: item.value,
                                persen: Math.ceil((item.value / response.data.countAsset) * 100),
                            }
                        });

                        const dataSummaryKondisi = response.data.dataSummaryChartAssetByKondisi.map((
                            item) => {
                            return {
                                name: item.name,
                                value: item.value,
                                persen: Math.ceil((item.value / response.data.countAsset) * 100),
                            }
                        });

                        generateChartAssetSummary(dataSummaryAsset);

                        generateChartAssetKondisi(dataSummaryKondisi);

                        generateChartPenerimaanAsset(response.data.dataSummaryChartAssetByMonthRegis);

                        generateChartService(response.data.dataSummaryServiceByStatus);

                        generatechartNilaiBukuAset(response.data.dataNilaiBukuAsset);

                        generatechartPerolehabAset(response.data.dataNilaiPerolehan);
                    }
                },
            })
        }

        const formatNumberToMilion = (number) => {
            let satuan = '';
            let pembagi = 1;

            if (number >= 1000000000) {
                satuan = 'M';
                pembagi = 1000000000;
            } else if (number >= 1000000) {
                satuan = 'Jt';
                pembagi = 1000000;
            } else if (number >= 1000) {
                satuan = 'Rb';
                pembagi = 1000;
            }

            let numberFormat = formatNumber((number / pembagi).toFixed(2));

            return numberFormat + ' ' + satuan;
        }

        const generateChartAssetSummary = (data) => {
            echarts.init(document.querySelector("#chartAssetSummary")).setOption({
                title: {
                    show: false,
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    show: false,
                },
                padding: 0,
                series: [{
                    name: 'Kelompok Aset',
                    type: 'pie',
                    radius: ['50%', '60%'],
                    data: data,
                    // color: ['#45C277', '#FA394C', '#FFC102'],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            });
        }

        const generatechartNilaiBukuAset = (data) => {
            echarts.init(document.querySelector("#chartNilaiBukuAset")).setOption({
                title: {
                    show: false,
                },
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        return `${params.name} : <br/> Rp ${formatNumber(params.value)}`;
                    }
                },
                legend: {
                    show: false,
                },
                padding: 0,
                series: [{
                    name: 'Kelompok Aset',
                    type: 'pie',
                    radius: ['50%', '60%'],
                    data: data,
                    // color: ['#45C277', '#FA394C', '#FFC102'],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            });
        }

        const generatechartPerolehabAset = (data) => {
            echarts.init(document.querySelector("#chartNilaiPerolehanAset")).setOption({
                title: {
                    show: false,
                },
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        return `${params.name} : <br/>Rp ${formatNumber(params.value)}`;
                    }
                },
                legend: {
                    show: false,
                },
                padding: 0,
                series: [{
                    name: 'Kelompok Aset',
                    type: 'pie',
                    radius: ['50%', '60%'],
                    data: data,
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                }]
            });
        }

        const generateChartAssetKondisi = (data) => {
            const backgroundArray = ['#228BE6', '#A6CEE3', '#FFC102', '#F03E3E', '#45C277'];
            let value = [];
            let name = [];
            $(data).each(function(index, item) {
                let itemData = {
                    value: item.value,
                    itemStyle: {
                        color: backgroundArray[index],
                    },
                }
                value.push(itemData);
                name.push(item.name);
            });
            echarts.init(document.querySelector("#chartAssetKondisi")).setOption({
                xAxis: {
                    type: 'value',
                },
                padding: 0,
                yAxis: {
                    type: 'category',
                    data: name,
                },
                grid: {
                    left: '10%',
                    containLabel: true,
                    top: '0%',
                    bottom: '0%',
                    right: '10%',
                },
                legend: {
                    show: false,
                },
                label: {
                    show: true,
                    position: 'inside',
                    fontWeight: 'bold',
                    color: '#fff',
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                series: [{
                    name: 'Kondisi Aset',
                    data: value,
                    type: 'bar',
                }]
            });
        }

        const generateChartPenerimaanAsset = (data) => {
            echarts.init(document.querySelector("#chartPenerimaanAsset")).setOption({
                xAxis: {
                    type: 'category',
                    data: data.name,
                },
                yAxis: {
                    type: 'value'
                },
                legend: {
                    show: false,
                },
                grid: {
                    left: '5%',
                    containLabel: true,
                    top: '5%',
                    bottom: '5%',
                    right: '5%',
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                series: [{
                    name: 'Penerimaan Aset',
                    data: data.value,
                    type: 'bar',
                    color: '#339AF0',
                }]
            });
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

        const generateChartService = (data) => {
            console.log('Data Service', data);
            echarts.init(document.querySelector("#chartSummaryService")).setOption({
                xAxis: {
                    type: 'value',
                },
                yAxis: {
                    type: 'category',
                    data: data.name,
                },
                grid: {
                    left: '10%',
                    containLabel: true,
                    top: '0%',
                    bottom: '20%',
                    right: '10%',
                },
                legend: {
                    show: false,
                },
                label: {
                    show: true,
                    position: 'inside',
                    fontWeight: 'bold',
                    color: '#fff',
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                series: [{
                    name: 'Services Aset',
                    data: data.data,
                    type: 'bar',
                }]
            });
        }
    </script>

    <script>
        var tableAsetPengambangan = $('#tableAsetPengambangan');

        $(document).ready(function() {
            tableAsetPengambangan.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                ordering: false,
                paging: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable') }}",
                    data: function(d) {
                        d.status_kondisi = 'pengembangan';
                        d.is_draft = '0';
                        d.global = true;
                        d.awal = $('.datepickerAwal').val();
                        d.akhir = $('.datepickerAkhir').val();
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
                        data: 'kode_asset'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'group'
                    },
                    {
                        data: 'nama_kategori'
                    },
                    {
                        data: "id",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'id'
                    },
                ],
                columnDefs: [{
                    targets: 5,
                    render: function(data, type, row) {
                        return `
                                <a href="{{ route('admin.listing-asset.detail', ':id') }}" class="btn btn-sm btn-icon btn-primary" title="Detail Aset">
                                    <i class="fa fa-eye"></i>
                                </a>
                            `.replace(':id', data);
                    }
                }],
                createdRow: function(row, data, index) {

                },
                footerCallback: function(row, data, start, end, display) {

                }
            });
        });


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

        const filterTableAsset = () => {
            const reset = $('#resetFilter').removeClass('d-none')
            datatableCriticalAduan.DataTable().ajax.reload();
            datatablePerencanaanServices.DataTable().ajax.reload();
            datatableServicesOnProgress.DataTable().ajax.reload();
            tableLogOpname.DataTable().ajax.reload();
            tableAsetPengambangan.DataTable().ajax.reload();
            getSummaryData();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const awal = $('.datepickerAwal').val(null);
            const akhir = $('.datepickerAkhir').val(null);

            datatableCriticalAduan.DataTable().ajax.reload();
            datatablePerencanaanServices.DataTable().ajax.reload();
            datatableServicesOnProgress.DataTable().ajax.reload();
            tableLogOpname.DataTable().ajax.reload();
            tableAsetPengambangan.DataTable().ajax.reload();
            getSummaryData();
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-6">
            <h5 class="text-primary mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.933" height="20.933" viewBox="0 0 20.933 20.933">
                    <path id="Icon_material-pie-chart-outlined" data-name="Icon material-pie-chart-outlined"
                        d="M13.466,3A10.466,10.466,0,1,0,23.933,13.466,10.5,10.5,0,0,0,13.466,3Zm1.047,2.167a8.386,8.386,0,0,1,7.253,7.253H14.513Zm-9.42,8.3a8.394,8.394,0,0,1,7.327-8.3v16.61A8.413,8.413,0,0,1,5.093,13.466Zm9.42,8.3V14.513h7.253A8.375,8.375,0,0,1,14.513,21.766Z"
                        transform="translate(-3 -3)" fill="#0067d4" />
                </svg>
                <strong>Data Summary</strong>
            </h5>
        </div>
        <div class="col-md-10 col-6">
            <div class="d-flex justify-content-end align-items-end mb-3">
                <div class="d-flex align-items-center">
                    <button onclick="openModalByClass('modalFilterAsset')" class="btn btn-sm btn-info shadow-custom"
                        type="button"><i class="fas fa-sliders-h mr-2"></i>
                        Filter</button>
                    <button onclick="resetFilterData()" id="resetFilter"
                        class="btn btn-sm d-none btn-danger shadow-custom mr-2 ml-2" type="button"><i
                            class="fas fa-sync"></i>Reset</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="kt-portlet shadow-custom">
                        <div class="kt-portlet__head px-4">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Asset Value
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body" style="height: 280px;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span style="background-color: #C3D8EC; border-color: #C3D8EC;"
                                        class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fas fa-boxes text-light"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Total Aset</p>
                                </div>
                                <h2 class="text-dark mb-0 text-summary-dashboard"><strong id="totalAssetSummary">0</strong>
                                </h2>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="mr-3 kt-badge kt-badge--unified-brand kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fa fa-money-bill-wave"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Nilai Beli Asset</p>
                                </div>
                                <h2 class="text-dark mb-0 text-summary-dashboard"><strong id="nilaiBeliAsset">0 Jt</strong>
                                </h2>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fa fa-dollar-sign"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Total Depresiasi</p>
                                </div>
                                <h2 class="text-dark mb-0 text-summary-dashboard"><strong id="totalDepresiasiAsset">0
                                        Jt</strong></h2>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span style="background-color: #C3D8EC; border-color: #C3D8EC;"
                                        class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                        <i class="fa fa-dollar-sign text-light"></i>
                                    </span>
                                    <p class="mb-0 text-dark">Value Asset</p>
                                </div>
                                <h2 class="text-dark mb-0 text-summary-dashboard"><strong id="totalValueAsset">0 Jt</strong>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="kt-portlet shadow-custom">
                        <div class="kt-portlet__head px-4">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Jumlah Asset
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body px-0">
                            <div id="chartAssetSummary" style="height: 260px; margin-top: -30px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="kt-portlet shadow-custom">
                        <div class="kt-portlet__head px-4">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Nilai Buku Aset
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body px-0">
                            <div id="chartNilaiBukuAset" style="height: 260px; margin-top: -30px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="kt-portlet shadow-custom">
                        <div class="kt-portlet__head px-4">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Nilai Perolehan Aset
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body px-0">
                            <div id="chartNilaiPerolehanAset" style="height: 260px; margin-top: -30px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-3 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Asset Data
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div style="height: 115px;">
                        <h6>Total Asset Data</h6>
                        <h1 class="text-dark text-right"><strong id="totalAssetSummary">0</strong></h1>
                    </div>
                    <div style="height: 115px;">
                        <h6>Last Change</h6>
                        <p class="text-primary text-right"><strong id="lastUpdateAsset">-</strong></p>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Kondisi Asset
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body px-0">
                    <div id="chartAssetKondisi" style="height: 230px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Summary Penerimaan Asset
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body p-0">
                    <div id="chartPenerimaanAsset" style="height: 280px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Summary Pemeliharaan
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-4">
                            <div class="d-flex align-items-center border-bottom border-success py-2">
                                <h2 class="text-success mb-0"><strong id="keluhan-total">0</strong></h2>
                                <h6 class="text-success ml-2 mb-0"><strong>Total Keluhan User</strong></h6>
                            </div>
                            <div class="d-flex align-items-center border-bottom border-success py-2">
                                <h2 class="text-success mb-0"><strong id="keluhan-ditangani-total">0</strong></h2>
                                <h6 class="text-success ml-2 mb-0"><strong>Total Ditangani</strong></h6>
                            </div>
                            <div class="d-flex align-items-center border-bottom border-success py-2">
                                <h2 class="text-success mb-0"><strong id="keluhan-belum-total">0</strong></h2>
                                <h6 class="text-success ml-2 mb-0"><strong>Total Belum Ditangani</strong></h6>
                            </div>
                        </div>
                        <div class="col-8 p-0 m-0">
                            <div style="height: 250px;">
                                <div id="chartSummaryService" style="height: 300px; "></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Overview Pengaduan
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 300px;">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatableCriticalAduan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#</th>
                                    <th>Tanggal Aduan</th>
                                    <th>Nama Pembuat</th>
                                    <th>Level Aduan</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Perencanaan Services
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 300px;">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatablePerencanaanServices">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50px">#</th>
                                    <th>Tanggal Services</th>
                                    <th>Nama Aset</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            10 Kritikal Aset
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 300px;">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="tableLogOpname">
                            <thead>
                                <tr>
                                    <th>Kode Aset</th>
                                    <th>Deskripsi</th>
                                    <th>Status Akhir</th>
                                    <th>Tingkat Kritikal</th>
                                    <th>Catatan</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            10 Services sedang berlangsung
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 300px;">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatableServicesOnProgress">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50px">#</th>
                                    <th>Kode Services</th>
                                    <th>Tanggal Services</th>
                                    <th>Nama Aset</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Aset Dalam Pengembangan
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 300px;">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="tableAsetPengambangan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Aset</th>
                                    <th>Deskripsi</th>
                                    <th>Kelompok</th>
                                    <th>Jenis</th>
                                    <th>#</th>
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
    @include('pages.admin.dashboard._modal_create_service')
    @include('pages.admin.keluhan.components.modal._modal_edit_keluhan')
    @include('pages.admin.services.components.modal._modal_detail_service')
    @include('pages.admin.services.components.modal._modal_edit_status_service')
    @include('pages.admin.keluhan.components.modal._modal_detail_keluhan')
    @include('pages.admin.dashboard._modal_filter')
@endsection
