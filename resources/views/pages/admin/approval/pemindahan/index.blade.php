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
                        d.approvable_type = 'App\\Models\\PemindahanAsset';
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
                        data: "data_detail_approval.link_detail",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        data: 'approvable.tanggal_pemindahan'
                    },
                    {
                        data: 'data_detail_approval.nama_asset'
                    },
                    {
                        data: 'pembuat_approval'
                    },
                    {
                        data: 'data_detail_approval.jenis_asset'
                    },
                    {
                        data: 'data_detail_approval.penerima_asset'
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
                            let element = ` <button onclick="showDetail(this)" data-url_detail="` +
                                data + `" data-url_update="` + full.link_update + `" type="button" class="btn btn-sm btn-primary btn-icon" title="Detail">
                                                <i class="la la-eye"></i>
                                            </button>`;
                            if (full.is_approve == '1') {
                                element +=
                                    `<a href="${full.data_detail_approval.link_stream_bast}" class="btn ml-1 btn-sm btn-icon btn-success" target="_blank"><i class="fa fa-file"><i/></a>`
                            }

                            return element;
                        },
                    },
                    {
                        targets: [2],
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    },
                    {
                        targets: [7],
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

            @if(isset(request()->id))
                $('#idNotifikasi').data('url_detail', "{{ route('admin.listing-asset.pemindahan-asset.show', request()->id) }}");
                $('#idNotifikasi').data('url_update', "{{ route('admin.approval.pemindahan.change-status', request()->id) }}");
                showDetail($('#idNotifikasi'));
            @endif
        });

        const showDetail = (button) => {
            const url = $(button).data('url_detail');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data.pemindahan;
                        let kategori = response.data.kategori;
                        let modal = $('#modalDetailPeminjaman');
                        let form = modal.find('form');
                        $('#tanggalApproval').hide();
                        form.attr('action', url_update);
                        $('.isDisabled').attr('disabled', false);
                        const penerima = JSON.parse(data.json_penerima_asset);
                        const penyerah = JSON.parse(data.json_penyerah_asset);
                        const asset = JSON.parse(data.detail_pemindahan_asset.json_asset_data);
                        if (data.approval[0].is_approve == 1) {
                            $('.isDisabled').attr('disabled', true);
                            $('#tanggalApproval').val(data.approval[0].tanggal_approval).show();
                            const status_approval = data.approval[0].is_approve == '1' ? 'disetujui' :
                                'ditolak';
                            $('#statusApproval option[value=' + status_approval + ']').attr('selected',
                                true);
                            $('#keteranganApproval').val(data.approval[0].keterangan);
                        }
                        const kategori_name = kategori ? kategori.nama_kategori : '-';
                        const group_name = kategori.group_kategori_asset ? kategori.group_kategori_asset
                            .nama_group : '-';

                        $('#groupAsset').text(group_name);
                        $('#kategoriAsset').text(kategori_name);

                        $('#deskripsiAsset').text(asset.deskripsi);
                        $('#nilaiPerolehanAsset').text(asset.nilai_perolehan);
                        $('#noSeriAsset').text(asset.no_seri);

                        $('#noBast').val(data.no_surat);
                        $('#tanggalPemindahan').val(formatDateIntoIndonesia(data.tanggal_pemindahan));

                        $('#namaPenerima').val(penerima.nama);
                        $('#jabatanPenerima').val(penerima.jabatan);
                        $('#unitPenerima').val(penerima.unit_kerja);

                        $('#namaPenyerah').val(penyerah.nama);
                        $('#jabatanPenyerah').val(penyerah.jabatan);
                        $('#unitPenyerah').val(penyerah.unit_kerja);

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
    <input type="hidden" data-url_detail="" data-url_update="" name="" id="idNotifikasi">
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
                                    <th>Tanggal</th>
                                    <th>Nama Asset</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Jenis Asset</th>
                                    <th>Diterima Oleh</th>
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
    @include('pages.admin.approval.pemindahan._modal_detail')
@endsection
