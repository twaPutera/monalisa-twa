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
                ajax: "{{ route('admin.permintaan-inventaris.datatable') }}",
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
                        name: 'tanggal_permintaan',
                        data: 'tanggal_permintaan'
                    },
                    {
                        name: 'kode_permintaan',
                        data: 'kode_permintaan'
                    },
                    {
                        name: 'tanggal_pengambilan',
                        data: 'tanggal_pengambilan'
                    },
                    {
                        name: 'no_memo',
                        data: 'no_memo'
                    },
                    {
                        name: 'user_pengaju',
                        data: 'user_pengaju'
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
                        name: 'status',
                        data: 'status'
                    },
                    {
                        name: 'alasan',
                        data: 'alasan'
                    }
                ],
                columnDefs: [{
                        targets: [2, 4],
                        render: function(data, type, full, meta) {
                            return data != 'Tidak Ada' ? formatDateIntoIndonesia(data) : '-';
                        },
                    },
                    {
                        targets: 9,
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
        });


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
                            Daftar Permintaan Bahan Habis Pakai
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
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Kode Permintaan</th>
                                    <th>Tanggal Pengambilan</th>
                                    <th>No Memo</th>
                                    <th>User Pengaju</th>
                                    <th>Unit Kerja</th>
                                    <th>Jabatan</th>
                                    <th>Status</th>
                                    <th>Alasan</th>
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
    @include('pages.admin.listing-inventaris.permintaan.components.modal._modal_detail_permintaan')
@endsection
