@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection

@section('custom_js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        var table = $('#datatableExample');
        $(document).ready(function() {
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.peminjaman.datatable') }}",
                    data: function(d) {
                        d.tanggal_awal = $('#tanggal_awal').val();
                        d.tanggal_akhir = $('#tanggal_akhir').val();
                        d.status_peminjaman = $("#status_peminjaman").val();
                        d.status_approval = $("#status_approval").val();
                        d.guid_peminjam_asset = $("#peminjamSelect2").val();
                        d.keyword = $("#searchPeminjaman").val();
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
                        data: 'code'
                    },
                    {
                        data: 'nama_peminjam'
                    },
                    {
                        data: 'tanggal_peminjaman'
                    },
                    {
                        data: 'tanggal_pengembalian'
                    },
                    {
                        data: 'rating'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'approval.is_approve'
                    },
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [7],
                        render: function(data, type, full, meta) {
                            let element = '';
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
                            } else if (data == 'duedate') {
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
                        targets: [8],
                        render: function(data, type, full, meta) {
                            let element =
                                '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Pending</span>';
                            if (data == '1') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Disetujui</span>';
                            } else if (data == '2') {
                                element =
                                    '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Ditolak</span>';
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
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
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
            table.DataTable().ajax.reload();
        }
        const generatePeminjamFilter = () => {
            $('#peminjamSelect2').select2({
                width: '100%',
                placeholder: 'Pilih Peminjam',
                dropdownParent: $('.modalFilterAsset'),
                ajax: {
                    url: '{{ route('admin.user-management.user.get-data-select2') }}',
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

        $('.modalFilterAsset').on('shown.bs.modal', function() {
            setTimeout(() => {
                generatePeminjamFilter();
            }, 2000);
        });

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const tanggal_awal = $('#tanggal_awal').val(null);
            const tanggal_akhir = $('#tanggal_akhir').val(null);
            const status_peminjaman = $("#status_peminjaman").val(null);
            const guid_peminjam_asset = $("#peminjamSelect2").val(null);
            const status_approval = $("#status_approval").val(null);
            const keyword = $("#searchPeminjaman").val(null);
            table.DataTable().ajax.reload();
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Data Peminjaman Asset
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
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="input-group mr-3" style="width: 250px;">
                            <input type="text" id="searchPeminjaman" onkeyup="filterTableAsset()" class="form-control form-control-sm"
                                placeholder="Search for...">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButtonAsset"
                                    type="button"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Nama Peminjam</th>
                                    <th>Tanggal Peminjaman</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>Rating</th>
                                    <th>Status Peminjaman</th>
                                    <th>Status Approval</th>
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
    @include('pages.admin.peminjaman-asset._modal_filter')
@endsection
