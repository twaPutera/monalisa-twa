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
        var table = $('#detailAssetData');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.pemutihan-asset.datatable.detail') }}",
                    data: function(d) {
                        d.is_pemutihan = '1';
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
                        data: "button_show_asset",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
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
                        name: 'jenis_asset',
                        data: 'jenis_asset'
                    },
                    {
                        name: 'is_inventaris',
                        data: 'is_inventaris'
                    },
                    {
                        name: 'lokasi_asset',
                        data: 'lokasi_asset'
                    },
                    {
                        name: 'kondisi_asset',
                        data: 'kondisi_asset'
                    },
                    {
                        name: 'keterangan_pemutihan',
                        data: 'keterangan_pemutihan'
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
                    table.DataTable().ajax.reload();
                    showToastSuccess('Sukses', data.message);
                    if (data.redirect && data.redirect != null) {
                        setTimeout(function() {
                            var redirect = "{{ route('admin.pemutihan-asset.store.detail', '') }}" +
                                "/" +
                                data.data.id;
                            location.assign(redirect);
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
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                    if (key == "file_asset_service") {
                        $('#preview-file-image-error').html(errors[key][0]);
                        $('#preview-file-image-error-update').html(errors[key][0]);
                    }
                }
            });
        });

        const filterTableService = () => {
            table2.DataTable().ajax.reload();
        }

        $('#file_asset_service').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-image-text').text(file.name);
        });
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.pemutihan-asset.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Daftar Asset yang Dalam Penghapusan</span>
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 detailAssetData" id="detailAssetData">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Kode Asset</th>
                                    <th>Deskripsi Asset</th>
                                    <th>Jenis Asset</th>
                                    <th>Tipe</th>
                                    <th>Lokasi Asset</th>
                                    <th>Kondisi Asset</th>
                                    <th>Keterangan Penghapusan Asset</th>
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
@endsection
