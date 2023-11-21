@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
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
                ajax: "{{ route('admin.user-management.user.datatable') }}",
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
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'is_active'
                    },
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: 5,
                        render: function (data, type, full, meta) {
                            var status = {
                                1: {
                                    'title': 'Aktif',
                                    'class': 'kt-badge--success'
                                },
                                0: {
                                    'title': 'Tidak Aktif',
                                    'class': ' kt-badge--danger'
                                },
                            };
                            if (typeof status[data] === 'undefined') {
                                return data;
                            }
                            return '<span class="kt-badge ' + status[data].class + ' kt-badge--inline kt-badge--pill">' + status[data].title + '</span>';
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

                if (formElement.attr('id') == 'formImportUser') {
                    $('.error-import-container').empty();
                    $(errors).each(function(index, value) {
                        let message =
                            `<li class="text-danger"><strong>Baris ${value.row} dalam kolom ${value.attribute} : </strong>${value.errors[0]}</li>`;
                        $('.error-import-container').append(message);
                    });
                    $('.error-import-asset').show();
                    // reset form
                    formElement[0].reset();
                }
            });
        });

        const edit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEdituser');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=name]').val(response.data.name);
                    form.find('input[name=email]').val(response.data.email);
                    form.find('input[name=username_sso]').val(response.data.username_sso);
                    form.find(`select[name=role] option[value="${response.data.role}"]`).prop('selected', true);
                    form.find(`input[name=status][value="${response.data.is_active}"]`).prop('checked', true);
                    form.find('input[name=unit_kerja]').val(response.data.unit_kerja);
                    form.find('input[name=jabatan]').val(response.data.jabatan);
                    modal.modal('show');
                }
            })
        }

        const changePassword = (element) => {
            const url = $(element).data('url');
            const modal = $('.modalChangePasswordUser');
            const form = modal.find('form');
            form.attr('action', url);
            modal.modal('show');
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
                            Data User
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button onclick="openModalByClass('modalImportAsset')" class="btn btn-success shadow-custom btn-sm mr-2" type="button"><i class="fa fa-file"></i> Import</button>
                                <button type="button" onclick="openModalByClass('modalCreateUser')" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data </button>
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
                                    <th width="150px">#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th width="100px">Role</th>
                                    <th width="100px">Status</th>
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
    @include('pages.admin.user-management.user._modal_create')
    @include('pages.admin.user-management.user._modal_edit')
    @include('pages.admin.user-management.user._modal_change_password')
    @include('pages.admin.user-management.user._modal_import')
@endsection
