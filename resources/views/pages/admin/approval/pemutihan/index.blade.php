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
                        d.approvable_type = 'App\\Models\\PemutihanAsset';
                        d.guid_approver = "{{ $user->id }}"
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
                        data: "approvable.no_memo"
                    },
                    {
                        data: 'tipe_approval'
                    },
                    {
                        data: 'is_approve'
                    },
                    {
                        data: 'pembuat_approval'
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return `
                                <button onclick="showDetail(this)" data-approvable_id="${full.approvable_id}" data-url_detail="` +
                                data + `" data-url_update="` +
                                full.link_update + `" type="button" class="btn btn-sm btn-primary btn-icon" title="Detail">
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
        const showPemutihanAsset = (button) => {
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
        @if (isset(request()->pemutihan_id))
            setTimeout(() => {
                $('button[data-approvable_id="{{ request()->pemutihan_id }}"]').click();
            }, 1000);
        @endif

        const showDetail = (button) => {
            const url = $(button).data('url_detail');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        // let user_peminjam = JSON.parse(data.json_peminjam_asset);
                        let modal = $('#modalDetailPeminjaman');
                        let form = modal.find('form');
                        $('#tanggalApproval').hide();
                        form.attr('action', url_update);
                        $('.isDisabled').attr('disabled', false);
                        if (data.approval.is_approve == 1) {
                            $('.isDisabled').attr('disabled', true);
                            $('#tanggalApproval').val(data.approval.tanggal_approval).show();
                            const status_approval = data.approval.is_approve == '1' ? 'disetujui' :
                                'ditolak';
                            $('#statusApproval option[value=' + status_approval + ']').attr('selected',
                                true);
                            $('#keteranganApproval').val(data.approval.keterangan);
                        }
                        // $('#namaPeminjam').val(user_peminjam.name);
                        $('#tanggalPemutihan').val(data.tanggal);
                        $('#beritaAcara').val(data.no_memo);
                        $('#keteranganUmum').val(data.keterangan);
                        $('#createdBy').val(data.created_by_name);
                        $('#fileBeritaAcara').attr('href',
                            "{{ route('admin.pemutihan-asset.store.detail.download') . '?filename=' . '' }}" +
                            data.file_bast);
                        $('#tableBodyDetailPeminjaman').html('');
                        $(data.detail_pemutihan_asset).each(function(index, value) {
                            let buttonImage =
                                `
                                <a href="#" onclick="showPemutihanAsset(this)"
                                data-url_detail="{{ route('admin.pemutihan-asset.edit.listing-asset.get-image', '') }}/` +
                                value.id + `"
                                class="btn btn-sm btn-icon"><i class="fa fa-image"></i></a>
                            `;
                            const kode_asset = JSON.parse(value.json_asset).kode_asset ? JSON.parse(
                                    value.json_asset)
                                .kode_asset : 'Tidak Ada';
                            const deskripsi = JSON.parse(value.json_asset).deskripsi ? JSON.parse(
                                    value.json_asset)
                                .deskripsi : 'Tidak Ada';
                            const lokasi = JSON.parse(value.json_asset).lokasi ? JSON.parse(value
                                    .json_asset).lokasi
                                .nama_lokasi : 'Tidak Ada';

                            let element = `
                                <tr>
                                    <td>` + (index + 1) + `</td>
                                    <td>` + buttonImage + `</td>
                                    <td>` + kode_asset + `</td>
                                    <td>` + deskripsi + `</td>
                                    <td>` + lokasi + `</td>
                                    <td>` + value.keterangan_pemutihan + `</td>
                                </tr>
                            `;
                            $('#tableBodyDetailPeminjaman').append(element);
                        });

                        $('#modalDetailPeminjaman').modal('show');
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
                                    <th>No BAST</th>
                                    <th>Jenis Penghapusan</th>
                                    <th>Status</th>
                                    <th>Pemohon</th>
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
    @include('pages.admin.approval.pemutihan._modal_detail')
    @include('pages.admin.pemutihan-asset.components.modal._modal_preview')
@endsection
