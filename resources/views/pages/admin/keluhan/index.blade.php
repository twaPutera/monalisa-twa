@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    {{-- <style>
        div.dataTables_wrapper {
            width: 100% !important;
        }
    </style> --}}
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        var table = $('#datatableExample');
        @if (isset(request()->pengaduan_id))
            setTimeout(() => {
                $('button[data-pengaduan_id="{{ request()->pengaduan_id }}"]').click();
            }, 1000);
        @endif

        $(document).ready(function() {

            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.keluhan.datatable') }}",
                    data: function(d) {
                        d.id_lokasi = $('#lokasiAssetCreateService').val();
                        d.id_asset = $('#listAssetLocation').val();
                        d.status_pengaduan = $("#statusPengaduanFilter").val();
                        d.prioritas_pengaduan = $("#prioritasPengaduanFilter").val();
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
                        name: 'tanggal_keluhan',
                        data: 'tanggal_keluhan'
                    },
                    {
                        name: 'kode_pengaduan',
                        data: 'kode_pengaduan'
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
                        name: 'gambar_pengaduan',
                        data: 'gambar_pengaduan'
                    },
                    {
                        name: 'created_by_name',
                        data: 'created_by_name'
                    },
                    {
                        name: 'status_pengaduan',
                        data: 'status_pengaduan'
                    },
                    {
                        name: 'catatan_admin',
                        data: 'catatan_admin'
                    },

                ],
                columnDefs: [{
                        targets: 10,
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
                    if (key == "file_pendukung") {
                        $('#preview-file-error').html(errors[key][0]);
                    }
                }
            });


        });

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

        const edit = (button) => {
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
                        form.find('input[name=nama_asset]').val(response.data.asset_data.deskripsi);
                        if (response.data.asset_data.lokasi != null) {
                            form.find('input[name=lokasi_asset]').val(response.data.asset_data.lokasi
                                .nama_lokasi);
                        }
                        if (response.data.asset_data.kategori_asset != null) {
                            form.find('input[name=kelompok_asset]').val(response.data.asset_data
                                .kategori_asset
                                .group_kategori_asset.nama_group);
                            form.find('input[name=jenis_asset]').val(response.data.asset_data.kategori_asset
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
        const filterTableAsset = () => {
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const id_lokasi = $('#lokasiAssetCreateService').val(null);
            const id_asset = $('#listAssetLocation').val(null);
            const status_pengaduan = $("#statusPengaduanFilter").val(null);
            const prioritas_pengaduan = $("#prioritasPengaduanFilter").val(null);
            table.DataTable().ajax.reload();
        }

        $('#file_pendukung').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });
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
    </script>
    @include('pages.admin.keluhan.components.js._script_modal_create')
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Data Pengaduan
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button onclick="openModalByClass('modalFilterAsset')"
                                    class="btn btn-sm btn-info shadow-custom" type="button"><i
                                        class="fas fa-sliders-h mr-2"></i>
                                    Filter</button>
                                <button onclick="resetFilterData()" id="resetFilter"
                                    class="btn btn-sm d-none btn-danger shadow-custom mr-2 ml-2" type="button"><i
                                        class="fas fa-sync"></i>Reset</button>
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
                                    <th width="100px">#</th>
                                    <th>Tanggal Pengaduan</th>
                                    <th>Kode Pengaduan</th>
                                    <th>Nama Asset</th>
                                    <th>Lokasi Asset</th>
                                    <th>Prioritas Pengaduan</th>
                                    <th>Catatan Pengaduan</th>
                                    <th>Gambar Pengaduan</th>
                                    <th>Dilaporkan Oleh</th>
                                    <th>Status Pengaduan</th>
                                    <th>Catatan Admin</th>
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
    @include('pages.admin.keluhan.components.modal._modal_preview')
    @include('pages.admin.keluhan.components.modal._modal_edit_keluhan')
    @include('pages.admin.keluhan.components.modal._modal_filter')
    @include('pages.admin.keluhan.components.modal._modal_detail_keluhan')
@endsection
