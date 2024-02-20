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
            width: 300% !important;
        }

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
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
                ordering: true,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable.report') }}",
                    data: function(d) {
                        d.id_lokasi = $('#lokasiAssetCreateService').val();
                        d.id_kategori_asset = $('#listKategoriAssetLocation').val();
                        d.searchKeyword = $('#searchAsset').val();
                        d.is_draft = '0';
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
                        data: 'kode_asset'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'is_inventaris',
                        render: function(type) {
                            return type == 1 ? 'Inventaris' : 'Aset';
                        }
                    },
                    {
                        data: 'group'
                    },
                    {
                        data: 'nama_kategori'
                    },
                    {
                        data: 'status_kondisi'
                    },
                    {
                        data: 'status_akunting',
                        render: function(d) {
                            return d == null ? 'Tidak Ada' : d;
                        }
                    },
                    {
                        data: 'tanggal_perolehan'
                    },
                    {
                        data: 'nilai_perolehan'
                    },
                    {
                        data: 'tgl_pelunasan',
                        render: function(data) {
                            return data == null ? '-' : formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        data: 'nama_lokasi',
                        orderable: false,
                    },
                    {
                        data: 'owner_name',
                        orderable: false,
                    },
                    {
                        data: 'register_oleh',
                        orderable: false,
                    },
                    {
                        data: 'nama_satuan',
                        orderable: false,
                    },
                    {
                        data: 'nama_vendor',
                        orderable: false,
                    },
                    {
                        name: 'tanggal_opname',
                        data: 'tanggal_opname',
                        orderable: false,
                    },
                    {
                        name: 'kode_opname',
                        data: 'kode_opname',
                        orderable: false,
                    },
                    {
                        name: 'catatan_opname',
                        data: 'catatan_opname',
                        orderable: false,
                    },
                    {
                        name: 'user_opname',
                        data: 'user_opname',
                        orderable: false,
                    },
                    {
                        name: 'tanggal_peminjaman',
                        data: 'tanggal_peminjaman',
                        orderable: false,
                    },
                    {
                        name: 'tanggal_pengembalian',
                        data: 'tanggal_pengembalian',
                        orderable: false,
                    },
                    {
                        name: 'status_peminjaman',
                        data: 'status_peminjaman',
                        orderable: false,
                    },
                    {
                        name: 'user_peminjaman',
                        data: 'user_peminjaman',
                        orderable: false,
                    },
                    {
                        name: 'tanggal_pemindahan',
                        data: 'tanggal_pemindahan',
                        orderable: false,
                    },
                    {
                        name: 'user_penyerah',
                        data: 'user_penyerah',
                        orderable: false,
                    },
                    {
                        name: 'user_penerima',
                        data: 'user_penerima',
                        orderable: false,
                    },
                    {
                        data: 'no_sp3'
                    }
                ],
                columnDefs: [{
                        targets: [9, 17, 21, 22, 25],
                        render: function(data, type, full, meta) {
                            if (data != '-') {
                                return formatDateIntoIndonesia(data);
                            }
                            return data;
                        }
                    },
                    {
                        targets: 10,
                        render: function(data, type, full, meta) {
                            return formatNumber(data);
                        }
                    },
                    {
                        targets: 23,
                        render: function(data, type, full, meta) {
                            let element = '-';
                            if (data == 'disetujui') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Disetujui</span>';
                            } else if (data == 'ditolak') {
                                element =
                                    '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Ditolak</span>';
                            } else if (data == 'pending') {
                                element =
                                    '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Pending</span>';
                            } else if (data == 'dipinjam') {
                                element =
                                    '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded">Sedang Dipinjam</span>';
                            } else if (data == 'terlambat') {
                                element =
                                    '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Terlambat</span>';
                            } else if (data == 'diproses') {
                                element =
                                    '<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Diproses</span>';
                            } else if (data == 'selesai') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Selesai</span>';
                            }
                            return element;
                        },
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            let element = '';
                            if (data == 'rusak') {
                                element =
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Rusak</span>`;
                            } else if (data == 'maintenance') {
                                element =
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Maintenance</span>`;
                            } else if (data == 'tidak-lengkap') {
                                element =
                                    `<span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Lengkap</span>`;
                            } else if (data == 'pengembangan') {
                                element =
                                    `<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Pengembangan</span>`;
                            } else {
                                element =
                                    `<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>`;
                            }

                            return element;
                        }
                    },
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
            generateLocationServiceSelect();
            generateLocationAsset();
            exportData();
            $("#searchAsset").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableService();
            });

            getSummaryOverview();
        });

        const exportData = () => {
            let id_lokasi = $('#lokasiAssetCreateService').val();
            let id_kategori_asset = $('#listKategoriAssetLocation').val();
            $('#id_lokasi_export').val(id_lokasi);
            $('#id_kategori_asset_export').val(id_kategori_asset);

        }
        const filterTableService = () => {
            exportData();
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const id_lokasi = $('#lokasiAssetCreateService').val(null);
            const id_kategori_asset = $('#listKategoriAssetLocation').val(null);
            const searchKeyword = $('#searchAsset').val(null);
            table.DataTable().ajax.reload();
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

        const getSummaryOverview = () => {
            $.ajax({
                url: '{{ route('admin.report.summary-asset.get-summary-overview') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#totalPenerimaan').text(
                            `Rp. ${formatNumber(response.data.asset.nilai_beli_asset)}`);
                        $('#totalValue').text(`Rp. ${formatNumber(response.data.asset.nilai_value_asset)}`);
                        $('#totalDepresiasi').text(
                            `Rp. ${formatNumber(response.data.asset.nilai_depresiasi)}`);
                        $('#avgDepresiasi').text(`${response.data.avg_depresiasi} %`);
                    }
                }
            })
        }
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
                            Summary Asset
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <form action="{{ route('admin.report.summary-asset.download-export') }}" method="get">
                                    <div class="d-flex align-items-center mt-2 mb-2">
                                        <input type="hidden" name="id_lokasi" id="id_lokasi_export">
                                        <input type="hidden" name="id_kategori_asset" id="id_kategori_asset_export">
                                        <button type="button" onclick="openModalByClass('modalFilterAsset')"
                                            class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter </button>
                                        <button onclick="resetFilterData()" id="resetFilter"
                                            class="btn btn-sm d-none btn-danger shadow-custom ml-2" type="button"><i
                                                class="fas fa-sync"></i>Reset</button>
                                        <button class="btn btn-success ml-1 shadow-custom btn-sm ml-2" type="submit"><i
                                                class="fas fa-print"></i>
                                            Export Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <div class="mb-3 row">
                        <div class="col-md-3 col-12">
                            <div class="p-3 border-primary border rounded">
                                <p>Total Penerimaan</p>
                                <h5 class="text-right text-primary" id="totalPenerimaan">Rp. 5.000.000</h5>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="p-3 border-danger border rounded">
                                <p>Total Depresiasi</p>
                                <h5 class="text-right text-danger" id="totalDepresiasi">Rp. 5.000.000</h5>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="p-3 border-success border rounded">
                                <p>Total Value Aset</p>
                                <h5 class="text-right text-success" id="totalValue">Rp. 5.000.000</h5>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="p-3 border-warning border rounded">
                                <p>Average Depresiasi</p>
                                <h5 class="text-right text-warning" id="avgDepresiasi">30%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="input-group mr-3" style="width: 250px;">
                                <input type="text" id="searchAsset" onkeyup="filterTableService()"
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
                        <table class="table table-striped mb-0" id="datatableLogService">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50px">#</th>
                                    <th width="150px">Kode</th>
                                    <th width="200px">Deskripsi</th>
                                    <th width="200px">Tipe</th>
                                    <th width="150px">Asset Group</th>
                                    <th width="150px">Jenis Asset</th>
                                    <th width="180px">Status Kondisi</th>
                                    <th width="180px">Status Akunting</th>
                                    <th width="100px">Tgl. Perolehan</th>
                                    <th width="150px">Nilai Perolehan</th>
                                    <th width="100px">Tgl. Pelunasan</th>
                                    <th width="150px">Lokasi</th>
                                    <th width="150px">Ownership</th>
                                    <th width="150px">Register Oleh</th>
                                    <th width="150px">Satuan</th>
                                    <th width="150px">Vendor</th>
                                    <th width="150px">Tgl. Opname Terakhir</th>
                                    <th width="150px">Kode Opname Terakhir</th>
                                    <th width="150px">Catatan Opname Terakhir</th>
                                    <th width="150px">User Opname Terakhir</th>
                                    <th width="150px">Tgl. Peminjaman Terakhir</th>
                                    <th width="150px">Tgl. Pengembalian Peminjaman Terakhir</th>
                                    <th width="150px">Status Peminjaman Terakhir</th>
                                    <th width="150px">User Peminjaman Terakhir</th>
                                    <th width="150px">Tgl. Pemindahan Asset Terakhir</th>
                                    <th width="150px">User Penyerah Asset</th>
                                    <th width="150px">User Penerima Asset</th>
                                    <th width="150px">No SP3</th>
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
    @include('pages.admin.report.asset._modal_filter_asset')
@endsection
