@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link href="{{ asset('assets/vendors/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var table = $('#datatableExample');
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.setting.lokasi.datatable') }}",
                    data: function(d) {
                        d.id_parent_lokasi = $('#lokasiParentId').val();
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
                        data: 'parent'
                    },
                    {
                        data: 'kode_lokasi'
                    },
                    {
                        data: 'nama_lokasi'
                    },
                ],
                columnDefs: [
                    //Custom template data
                ],
            });

            $('#lokasiTree').jstree({
                "core": {
                    "themes": {
                        "responsive": false
                    },
                    // so that create works
                    "check_callback": function(e) {
                        console.log(e);
                    },
                    'data': {
                        url: "{{ route('admin.setting.lokasi.get-node-tree') }}"
                    }
                },
                "types": {
                    "default": {
                        "icon": "fa fa-map-marker kt-font-success"
                    },
                    "file": {
                        "icon": "fa fa-file  kt-font-success"
                    }
                },
                "plugins": ["dnd", "types", "search", "adv_search"]
            }).on('changed.jstree', function(e, data) {
                $('#lokasiParentId').val(data.selected[0]);
                $('.select2Lokasi option[value="' + data.selected[0] + '"]').attr('selected', 'selected');
                table.DataTable().ajax.reload();
            });

            $('#searchButton').on('click', function() {
                $('#lokasiTree').jstree('search', $('#searchTree').val());
            });

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    table.DataTable().ajax.reload();
                    $('#lokasiTree').jstree(true).refresh();
                    getDataOptionSelect();
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

            $('.modalCreateLokasi').on('shown.bs.modal', function(e) {
                getDataOptionSelect();
                generateSelect2Lokasi();
            });

            getDataOptionSelect();
        });

        const getDataOptionSelect = (id = null) => {
            $.ajax({
                url: "{{ route('admin.setting.lokasi.get-select2') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const select = $('.select2Lokasi');
                    select.empty();
                    response.data.forEach(element => {
                        let selected = '';
                        if (element.id == $('#lokasiParentId').val()) {
                            selected = 'selected';
                        }
                        if (id != null && id != element.id) {
                            select.append(
                                `<option ${selected} value="${element.id}">${element.text}</option>`
                            );
                        }

                        if (id == null) {
                            select.append(
                                `<option ${selected} value="${element.id}">${element.text}</option>`
                            );
                        }
                    });
                }
            })
        }

        const generateSelect2Lokasi = () => {
            $('.select2Lokasi').select2({
                'placeholder': 'Pilih Lokasi',
                'allowClear': true,
                'width': '100%'
            })

            // select2('val', $('#lokasiParentId').val());
        }

        const edit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditLokasi');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=kode_lokasi]').val(response.data.kode_lokasi);
                    form.find('input[name=nama_lokasi]').val(response.data.nama_lokasi);
                    form.find('textarea[name=keterangan]').val(response.data.keterangan);
                    modal.on('shown.bs.modal', function(e) {
                        getDataOptionSelect(response.data.id);
                        generateSelect2Lokasi();
                        form.find('select[name="parent_id"]').select2('val', response.data
                            .parent_id);
                    });
                    modal.modal('show');
                }
            })
        }
    </script>
@endsection
@section('main-content')
    <input type="hidden" name="" value="root" id="lokasiParentId">
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.settings.menu')
        </div>
        <div class="col-md-7 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Data Lokasi Asset
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalCreateLokasi')"
                                    class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Data </button>
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
                                    <th>Induk Lokasi</th>
                                    <th>Kode</th>
                                    <th>Nama Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Tree Lokasi
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body scroll-bar">
                    <div class="input-group mb-2">
                        <input type="text" id="searchTree" class="form-control" placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" id="searchButton" type="button"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div id="lokasiTree"></div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.settings.lokasi._modal_create')
    @include('pages.admin.settings.lokasi._modal_edit')
@endsection
