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
                searching: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.approval.datatable') }}",
                    data: function(d) {
                        // d.is_approve = null;
                        d.approvable_type = 'App\\Models\\RequestInventori'
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
                        data: "link_detail",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        data: 'pembuat_approval'
                    },
                    {
                        data: 'approvable.tanggal_pengambilan'
                    },
                    {
                        data: 'is_approve'
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return `
                                <button onclick="showDetail(this)" data-is_approve="${full.is_approve}" data-keterangan="${full.keterangan}" data-tanggal_approval="${full.tanggal_approval}" data-url_detail="` +
                                data + `" data-url_update="` + full.link_update + `" type="button" class="btn btn-sm btn-primary btn-icon" title="Detail">
                                    <i class="la la-eye"></i>
                                </button>
                            `;
                        },
                    },
                    {
                        targets: [4],
                        render: function(data, type, full, meta) {
                            let element =
                                '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Pending</span>';
                            if (data == '1') {
                                element =
                                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Disetujui</span>';
                            } else if (data == '0') {
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
                    if (data.data.request_inventori.status == 'diproses') {
                        setTimeout(() => {
                            window.location.href = data.data.url
                        }, 2000);
                    }
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

            @if (isset(request()->peminjaman_id))
                alert('test');
                setTimeout(() => {
                    $('button[data-approvable_id="{{ request()->peminjaman_id }}"]').click();
                }, 1000);
            @endif
        });

        const showDetail = (button) => {
            const url = $(button).data('url_detail');
            const url_update = $(button).data('url_update');
            const is_approve = $(button).data('is_approve');
            const tanggal_approval = $(button).data('tanggal_approval');
            const keterangan = $(button).data('keterangan');
            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: function() {
                    $('.isDisabled').attr('disabled', false);
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data);
                        let modal = $('#modalDetailRequestInventori');
                        let form = modal.find('form');
                        form.attr('action', url_update);

                        $('#namaPengaju').val(response.data.pengaju.name);
                        $('#tanggalPengambilan').val(response.data.request.tanggal_pengambilan);
                        $('#unitKerjaPengaju').val(response.data.request.unit_kerja);
                        $('#jabatanPengaju').val(response.data.request.jabatan);
                        $('#noMemo').val(response.data.request.no_memo);
                        $("#alasanPengajuan").val(response.data.request.alasan);

                        $('#tableBodyDetailRequestInventori').html('');
                        response.data.request.detail_request_inventori.forEach((item, index) => {
                            $('#tableBodyDetailRequestInventori').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.inventori.nama_inventori}</td>
                                    <td>${item.qty}</td>
                                </tr>
                            `);
                        });

                        if (is_approve != null) {
                            $('.isDisabled').attr('disabled', true);
                            $('#tanggalApproval').val(tanggal_approval).show();
                            const status_approval = is_approve == '1' ? 'disetujui' : 'ditolak';
                            console.log(status_approval)
                            $('#statusApproval option[value=' + status_approval + ']').attr('selected',true);
                            $('#keteranganApproval').val(keterangan);
                        }

                        modal.modal('show');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4" style="box-shadow: unset !important;">
                    <div class="kt-portlet__head-label">
                        <h4>Approval Task (<strong style="text-primary"><span class="approval-task-count">0</span>
                                Task</strong>)</h4>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div>
                        @include('pages.admin.approval.tab-header')
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped dt_table" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="100px">#</th>
                                    <th>Nama Pengaju</th>
                                    <th>Tanggal Pengambilan</th>
                                    <th>Status</th>
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
    @include('pages.admin.approval.request-inventori._modal_detail')
@endsection
