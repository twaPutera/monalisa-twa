@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
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
                ajax: "{{ route('admin.setting.kategori-asset.datatable') }}",
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
                        data: 'group'
                    },
                    {
                        data: 'kode_kategori'
                    },
                    {
                        data: 'nama_kategori'
                    },
                    {
                        data: 'umur_asset',
                        render: function(o) {
                            return o + " Tahun";
                        }
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
                    generateGroupSelect();
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

            generateGroupSelect();

            $('.modalCreateKategoriAsset').on('shown.bs.modal', function(e) {
                generateGroupSelect();
                const form = $(this).find('form');
                $('.selectGroup').select2({
                    width: '91%',
                    placeholder: 'Pilih Kelompok Asset',
                    allowClear: true,
                    parent: $(this)
                }).on('change', function() {
                    const kode = $(this).find(':selected').data('kode');
                    console.log(kode);
                    form.find('input[name=kode_kategori]').val(kode);
                });

                $('.selectUmurAsset').select2({
                    width: '100%',
                    placeholder: 'Pilih Umur Asset',
                    allowClear: true,
                    parent: $(this)
                });
            })
        });

        const edit = (button) => {
            const url_edit = $(button).data('url_edit');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_edit,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const modal = $('.modalEditKategoriAsset');
                    const form = modal.find('form');
                    form.attr('action', url_update);
                    form.find('input[name=kode_kategori]').val(response.data.kode_kategori);
                    form.find('input[name=nama_kategori]').val(response.data.nama_kategori);
                    modal.on('shown.bs.modal', function(e) {
                        // generateGroupSelect();
                        $('#selectGroupEdit option[value="' + response.data
                            .id_group_kategori_asset + '"]').attr('selected', 'selected');
                        $('#selectGroupEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Kelompok Asset',
                            allowClear: true,
                            parent: $(this)
                        }).on('change', function() {
                            const kode = $(this).find(':selected').data('kode');
                            console.log(kode);
                            form.find('input[name=kode_kategori]').val(kode);
                        });
                        // $('#selectGroupEdit').select2('val', response.data.id_group_kategori_asset);
                        $('#selectUmurAssetEdit option[value="' + response.data.umur_asset + '"]')
                            .prop('selected', 'selected');
                        $('#selectUmurAssetEdit').select2({
                            width: '100%',
                            placeholder: 'Pilih Umur Asset',
                            allowClear: true,
                            parent: $(this)
                        });
                    })
                    modal.modal('show');
                }
            })
        }

        const generateGroupSelect = () => {
            $.ajax({
                url: "{{ route('admin.setting.group-kategori-asset.find-all') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('.selectGroup');
                        select.empty();
                        select.append(`<option value="">Pilih Group</option>`);
                        response.data.forEach((item) => {
                            select.append(`<option value="${item.id}" data-kode="${item.kode_group}">${item.nama_group}</option>`);
                        });
                    }
                }
            })
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.settings.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Data Jenis Asset
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <button type="button" onclick="openModalByClass('modalCreateKategoriAsset')"
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
                                    <th>Kelompok Asset</th>
                                    <th>Kode Jenis Asset</th>
                                    <th>Nama Jenis Asset</th>
                                    <th>Masa Manfaat Komersial</th>
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
    @include('pages.admin.settings.kategori-asset._modal_create')
    @include('pages.admin.settings.kategori-asset._modal_edit')
    @include('pages.admin.settings.group-kategori-asset._modal_create')
@endsection
