@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
@endsection
@section('custom_css')
    <style>
        .dataTables_wrapper .dataTable {
            margin: 0 !important;
        }

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        #tableProperti th,
        #tableProperti td {
            font-size: 12px;
        }

        th,
        td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
@endsection
@section('custom_js')
    <script>
        $(document).ready(function() {
            var table = $('#datatableLogService');
            table.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-inventaris.datatable.penambahan') }}",
                    data: function(d) {
                        d.id_inventaris = "{{ $listing_inventaris->id }}"
                    }
                },
                columns: [{
                        data: 'tanggal'
                    },
                    {
                        name: 'jumlah',
                        data: 'jumlah'
                    },
                    {
                        data: 'harga_beli'
                    },
                    {
                        data: 'created_by'
                    },

                ],
                columnDefs: [
                    //Custom template data
                ],
            });

            var table2 = $('#datatableLogPengurangan');
            table2.DataTable({
                responsive: true,
                processing: true,
                searching: false,
                ordering: false,
                serverSide: true,
                bLengthChange: false,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('admin.listing-inventaris.datatable.pengurangan') }}",
                    data: function(d) {
                        d.id_inventaris = "{{ $listing_inventaris->id }}"
                    }
                },
                columns: [{
                        data: 'tanggal'
                    },
                    {
                        data: 'no_memo'
                    },
                    {
                        name: 'jumlah',
                        data: 'jumlah'
                    },
                    {
                        data: 'created_by'
                    },

                ],
                columnDefs: [
                    //Custom template data
                ],
            });
        });
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="d-flex  justify-content-between align-items-center mb-3">
                        <h6 class="text-primary mb-0"><strong>Detail Bahan Habis Pakai -
                                {{ $listing_inventaris->nama_inventori }}</strong></h6>
                    </div>
                    <div class="pt-3 pb-1  assetProperti" style="border-radius: 9px; background: #E5F3FD;">
                        <table id="tableProperti" class="table table-striped">
                            <tr>
                                <td width="40%">Jenis Bahan Habis Pakai</td>
                                <td><strong>{{ $listing_inventaris->kode_inventori }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">Kategori Bahan Habis Pakai</td>
                                <td><strong>{{ $listing_inventaris->kategori_inventori->nama_kategori }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">Jumlah Bahan Habis Pakai Sebelumnya</td>
                                <td><strong>{{ $listing_inventaris->jumlah_sebelumnya }}
                                        {{ $listing_inventaris->satuan_inventori->nama_satuan }}</strong></td>
                            </tr>
                            <tr>
                                <td width="40%">Jumlah Bahan Habis Pakai Saat Ini</td>
                                <td><strong>{{ $listing_inventaris->jumlah_saat_ini }}
                                        {{ $listing_inventaris->satuan_inventori->nama_satuan }}</strong></td>
                            </tr>
                            <tr>
                                <td width="40%">Deskripsi Bahan Habis Pakai</td>
                                <td><strong>{{ $listing_inventaris->deskripsi_inventori }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <ul class="nav nav-tabs mb-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#" data-target="#kt_tabs_1_1">History
                                Data Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" data-target="#kt_tabs_1_2">History Data
                                Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="datatableLogService">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah Masuk</th>
                                            <th>Harga Beli</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="datatableLogPengurangan">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>No Memo</th>
                                            <th>Jumlah Keluar</th>
                                            <th>Created By</th>
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
        </div>
    </div>
@endsection
