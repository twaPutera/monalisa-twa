@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link href="{{ asset('assets/vendors/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('custom_css')
    <style>
        div.dataTables_wrapper {
            width: 200% !important;
        }

        #imgPreviewAsset {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .wahyu td{
            background-color: #ffcc00 !important;
            color:black !important;
        }

    </style>
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
@endsection
@section('custom_js')
    <script>
        var table = $('#datatableExample');
        $(document).ready(function() {
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    if (data.form == 'import') {
                        let modal = $('#modalImport');
                        let form = modal.find('form');
                        $('.error-import-container').empty();
                        $('.error-import-asset').hide();
                        form[0].reset();
                        modal.modal('hide');
                    }
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    showToastSuccess('Sukses', data.message);
                    $('#preview-file-error').html('');
                    $('#preview-file-error-edit').html('');
                    table.DataTable().ajax.reload();
                } else {

                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                    if (key == "gambar_asset") {
                        $('#preview-file-error').html(errors[key][0]);
                        $('#preview-file-error-edit').html(errors[key][0]);
                    }
                }
                
                if (formElement.attr('id') == 'formImportAsset') {
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

            table.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                // ordering: false,
                scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable') }}",
                    data: function(d) {
                        d.id_lokasi = $('#lokasiParentId').val();
                        d.id_satuan_asset = $('#satuanAssetFilter').val();
                        d.id_vendor = $('#vendorAssetFilter').val();
                        d.id_kategori_asset = $('#kategoriAssetFilter').val();
                        d.searchKeyword = $('#searchAsset').val();
                        d.is_sparepart = $('#isSparepartFilter').val();
                        d.is_pemutihan = $('#isPemutihanFilter').val();
                        d.is_draft = '1';
                        d.global = true;
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
                        data: "id",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'checkbox'
                    },
                    {
                        data: "id",
                        class: "text-center",
                        orderable: false,
                        searchable: false,
                        name: 'action'
                    },
                    {
                        data: 'kode_asset'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'is_inventaris',
                        render: function(type) {
                            return type == '1' ? 'Inventaris' : 'Aset';
                        }
                    },
                    {
                        data: 'is_it',
                        render: function(type) {
                            return type == '1' ? 'Barang IT' : 'Barang Aset';
                        }
                    },
                    {
                        data: 'group'
                    },
                    {
                        data: 'nama_kategori'
                    },
                    {
                        data: 'status_kondisi'
                    },
                    {
                        data: 'tanggal_perolehan',
                        render: function(data) {
                            return data == null ? '-' : data;
                        }
                    },
                    {
                        data: 'nilai_perolehan',
                        render: function(data) {
                            return data == null ? '-' : data;
                        }
                    },
                    {
                        data: 'tgl_pelunasan',
                        render: function(data) {
                            return data == null ? '-' : formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        data: 'nama_lokasi'
                    },
                    {
                        data: 'owner_name'
                    },
                    {
                        data: 'register_oleh'
                    },
                    {
                        data: 'nama_satuan'
                    },
                    {
                        data: 'nama_vendor'
                    },
                    {
                        data: 'updated_at'
                    },
                    {
                        data: 'nama_unit_kerja'
                    }
                ],
                columnDefs: [{
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return `<input type="checkbox" class="check-item" onchange="checklistAsset(this)" name="id_asset[]" value="${data}">`;
                        },
                    },
                    {
                        targets: 18,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            let url_detail = "{{ route('admin.listing-asset.show', ':id') }}";
                            let url_update =
                                "{{ route('admin.listing-asset.update.draft', ':id') }}";
                            let url_delete = "{{ route('admin.listing-asset.destroy', ':id') }}";
                            url_detail = url_detail.replace(':id', data);
                            url_update = url_update.replace(':id', data);
                            url_delete = url_delete.replace(':id', data);
                            let element = '';
                            element += `<form action="${url_delete}" method="POST">`;
                            element += `{{ csrf_field() }}`;
                            element += `
                                <button type="button" onclick="edit(this)" data-url_detail="${url_detail}" data-url_update="${url_update}" class="btn btn-sm btn-icon btn-warning"><i class="fa fa-edit"></i></button>
                            `;
                            element += `
                                <button type="button" onclick="deleteAsset(this)" data-url_delete="" class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                            `;
                            element += `</form>`;
                            return element;
                        }
                    },
                    {
                        targets: 10,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        targets: 9,
                        render: function(data, type, full, meta) {
                            let element = '';
                            if (data == 'rusak') {
                                element =
                                    `<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Rusak</span>`;
                            } else if (data == 'maintenance') {
                                element =
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Maintenance</span>`;
                            } else if (data == 'tidak-lengkap') {
                                element =
                                    `<span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Lengkap</span>`;
                            } else if (data == 'pengembangan') {
                                element =
                                    `<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Pengembangan</span>`;
                            } else if (data == 'draft') {
                                element =
                                    `<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Draft</span>`;
                            } else if (data == 'tidak-ditemukan') {
                                element =
                                    `<span class="kt-badge kt-badge--dark kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Ditemukan</span>`;
                            } else {
                                element =
                                    `<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>`;
                            }

                            return element;
                        }
                    },
                    {
                        targets: 11,
                        render: function(data, type, full, meta) {
                            return formatNumber(data);
                        }
                    },
                    {
                        targets: 19,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    }
                ],
                createdRow: function(row, data, index) {
                    //code tambahan oleh wahyu
                    if (data.jenis_penerimaan =='UMK') {
                        if(data.no_sp3 == null || data.no_sp3 == ""){
                        //$(this.node()).addClass('bg-warning'); // Add background color class
                            $(row).attr('data-id', data.id).addClass('wahyu');
                        }
                        //$(row).attr('data-id', data.id).addClass('row-asset').attr("style","cursor: pointer;");
                    }else if(data.jenis_penerimaan =='PO'){
                        if(data.no_memo_surat == null || data.no_memo_surat == "" || data.no_po == null || data.no_po == "" || data.no_sp3 == null || data.no_sp3 == ""){
                        
                            $(row).attr('data-id', data.id).addClass('wahyu');
                        }
                    }else if(data.jenis_penerimaan =='CC'){
                        if(data.no_memo_surat == null || data.no_memo_surat == "" || data.no_sp3 == null || data.no_sp3 == ""){
                        
                            $(row).attr('data-id', data.id).addClass('wahyu');
                        }
                    }else if(data.jenis_penerimaan =='Reimburse'){
                        if(data.no_memo_surat == null || data.no_memo_surat == "" || data.no_sp3 == null || data.no_sp3 == ""){
                        
                            $(row).attr('data-id', data.id).addClass('wahyu');
                        }
                    }
                    // $(row).attr('data-id', data.id).addClass('row-asset').attr("style",
                    //     "cursor: pointer;");
                    // $(row).on('click', function() {
                    //     alert(data.no_memo_surat);
                    // });
                },
                "drawCallback": function(settings) {
                    var api = this.api();
                    // var num_rows = api.page.info().recordsTotal;
                    var records_displayed = api.page.info().recordsDisplay;
                    let target = $('#totalFilterAktif');
                    target.empty();
                    target.append("Total " + records_displayed);
                    //alert(data.tanggal_pelunasan);

                    //tambahan dari wahyu untuk melengkapi requirement
                    // api.rows().every(function() {
                    //     var data = this.data();
                    //     var tanggalPelunasan = data.tanggal_pelunasan;

                    //     if (tanggalPelunasan === null) {
                    //         $(this.node()).addClass('bg-warning'); // Add background color class
                    //     } else {
                    //         $(this.node()).removeClass('bg-warning'); // Remove background color class
                    //     }
                    // });

                    setTimeout(function() {
                        rerenderCheckbox();
                    }, 100);
                },
                footerCallback: function(row, data, start, end, display) {
                    //
                }
            });

            $('.datepickerCreate').datepicker({
                todayHighlight: true,
                width: '100%',
                format: 'yyyy-mm-dd',
                autoclose: true,
            });


            $("#searchAsset").on("keydown", function(event) {
                if (event.which == 13)
                    filterTableAsset();
            });
        });

        const changeMemorandumStatus = (v) => {
            const memoAndin = $('#memo_andin');
            const memoManual = $('#memo_manual');
            if (v == "andin") {
                memoAndin.removeClass('d-none');
                memoManual.addClass('d-none');
            } else if (v == "manual") {
                memoManual.removeClass('d-none');
                memoAndin.addClass('d-none');
            } else {
                memoAndin.addClass('d-none');
                memoManual.addClass('d-none');
            }
        }

        const changeMemorandumStatusEdit = (v) => {
            const memoAndin = $('.memo_andin');
            const memoManual = $('.memo_manual');
            if (v == "andin") {
                memoAndin.removeClass('d-none');
                memoManual.addClass('d-none');
            } else if (v == "manual") {
                memoManual.removeClass('d-none');
                memoAndin.addClass('d-none');
            } else {
                memoAndin.addClass('d-none');
                memoManual.addClass('d-none');
            }
        }

        const checklistAsset = (element) => {
            let jsonTempAsset = JSON.parse($('#jsonTempAsset').val())
            if ($(element).is(':checked')) {
                jsonTempAsset.push($(element).val());
            } else {
                jsonTempAsset = jsonTempAsset.filter(item => item != $(element).val());
            }
            $('#jsonTempAsset').val(JSON.stringify(jsonTempAsset));
            $('#jsonTempAssetDelete').val(JSON.stringify(jsonTempAsset));
            $('#jsonTempAssetPublish').val(JSON.stringify(jsonTempAsset));
        }

        const rerenderCheckbox = () => {
            let jsonTempAsset = JSON.parse($('#jsonTempAsset').val());
            if (jsonTempAsset.length > 0) {
                jsonTempAsset.forEach(function(user_id) {
                    $(`input[name="id_asset[]"][value="${user_id}"]`).prop('checked', true);
                })
            }
        }

        const filterTableAsset = () => {
            const reset = $('#resetFilter').removeClass('d-none')
            table.DataTable().ajax.reload();
        }

        const resetFilterData = () => {
            const reset = $('#resetFilter').addClass('d-none')
            const id_lokasi = $('#lokasiParentId').val(null);
            const id_satuan_asset = $('#satuanAssetFilter').val(null);
            const id_vendor = $('#vendorAssetFilter').val(null);
            const id_kategori_asset = $('#kategoriAssetFilter').val(null);
            const searchKeyword = $('#searchAsset').val(null);
            const is_sparepart = $('#isSparepartFilter').val(null);
            const is_pemutihan = $('#isPemutihanFilter').val(null);
            table.DataTable().ajax.reload();
        }

        const edit = (button) => {
            const url_detail = $(button).data('url_detail');
            const url_update = $(button).data('url_update');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success == true) {
                        const data = response.data;
                        const modal = $('.modalEditDraftAsset');
                        const form = modal.find('form');
                        form.attr('action', url_update);
                        form.find('input[name="id"]').val(data.asset.id);
                        form.find('input[name="deskripsi"]').val(data.asset.deskripsi);
                        form.find('input[name="tanggal_perolehan"]').val(data.asset.tanggal_perolehan);
                        form.find('input[name="tanggal_pelunasan"]').val(data.asset.tgl_pelunasan);
                        form.find('input[name="kode_asset"]').val(data.asset.kode_asset);
                        form.find('input[name="nilai_perolehan"]').val(data.asset.nilai_perolehan);
                        // form.find('input[name="nilai_buku_asset"]').val(data.asset.nilai_buku_asset);
                        form.find('input[name="no_seri"]').val(data.asset.no_seri);
                        form.find('input[name="no_inventaris"]').val(data.asset.no_inventaris);
                        form.find('select[name="id_group_asset"]').append(
                            `<option value="${data.asset.kategori_asset.group_kategori_asset.id}" selected>${data.asset.kategori_asset.group_kategori_asset.nama_group}</option>`
                        );
                        form.find('select[name="id_kategori_asset"]').removeAttr('data-id_asset');
                        if (data.asset.kategori_asset != null && data.asset.kategori_asset != '') {
                            form.find('select[name="id_kategori_asset"]').append(
                                `<option value="${data.asset.kategori_asset.id}" selected>${data.asset.kategori_asset.nama_kategori} (${data.asset.kategori_asset.kode_kategori})</option>`
                            );
                            form.find('select[name="id_kategori_asset"]').attr('data-id_asset', data.asset
                                .id);
                        }
                        if (data.asset.lokasi != null && data.asset.lokasi != '') {
                            form.find('select[name="id_lokasi"]').append(
                                `<option value="${data.asset.lokasi.id}" selected>${data.asset.lokasi.nama_lokasi}</option>`
                            );
                        }
                        if (data.asset.satuan_asset != null && data.asset.satuan_asset != '') {
                            form.find('select[name="id_satuan_asset"]').append(
                                `<option value="${data.asset.satuan_asset.id}" selected>${data.asset.satuan_asset.nama_satuan}</option>`
                            );
                        }
                        if (data.asset.vendor != null && data.asset.vendor != '') {
                            form.find('select[name="id_vendor"]').append(
                                `<option value="${data.asset.vendor.id}" selected>${data.asset.vendor.nama_vendor}</option>`
                            );
                        }
                        if (data.asset.jenis_penerimaan != null && data.asset.jenis_penerimaan != '') {
                            form.find(
                                `select[name="jenis_penerimaan"] option[value="${data.asset.jenis_penerimaan}"]`
                            ).attr('selected', true);
                        }
                        if (data.asset.ownership != null && data.asset.ownership != '') {
                            form.find(`select[name="ownership"]`).append(
                                `<option value="${data.asset.ownership}" selected>${data.asset.owner_name}</option>`
                            );
                        }
                        if (data.asset.kelas_asset != null && data.asset.kelas_asset != '') {
                            form.find('select[name="id_kelas_asset"]').append(
                                `<option value="${data.asset.kelas_asset.id}" selected>${data.asset.kelas_asset.nama_kelas}</option>`
                            );
                        }
                        if (data.asset.status_kondisi != null && data.asset.status_kondisi != '') {
                            form.find(
                                `select[name="status_kondisi"] option[value="${data.asset.status_kondisi}"]`
                            ).prop('selected', true);
                        }
                        if (data.asset.status_akunting != null && data.asset.status_akunting != '') {
                            form.find(
                                `select[name="status_akunting"] option[value="${data.asset.status_akunting}"]`
                            ).prop('selected', true);
                        }
                        form.find('input[name="no_po"]').val(data.asset.no_po);
                        form.find('input[name="no_urut"]').val(data.asset.no_urut);
                        form.find('input[name="cost_center"]').val(data.asset.cost_center);
                        // form.find('input[name="call_center"]').val(data.asset.call_center);
                        form.find('input[name="no_sp3"]').val(data.asset.no_sp3);
                        form.find('textarea[name="spesifikasi"]').val(data.asset.spesifikasi);

                        if (data.asset.is_sparepart == '1') {
                            form.find('input[name="is_sparepart"]').prop('checked', true);
                        } else {
                            form.find('input[name="is_sparepart"]').prop('checked', false);
                        }

                        if (data.asset.is_pinjam == '1') {
                            form.find('input[name="is_pinjam"]').prop('checked', true);
                        } else {
                            form.find('input[name="is_pinjam"]').prop('checked', false);
                        }

                        if (data.asset.is_it == '1') {
                            form.find('input[name="is_it"]').prop('checked', true);
                        } else {
                            form.find('input[name="is_it"]').prop('checked', false);
                        }

                        if (data.asset.id_surat_memo_andin && data.asset.no_memo_surat) {
                            form.find('select[name="id_surat_memo_andin"]').append(
                                `<option value="${data.asset.id_surat_memo_andin}" selected>${data.asset.no_memo_surat}</option>`
                            );
                            form.find('input[name="no_memo_surat"]').val(data.asset.no_memo_surat);
                            form.find(
                                `select[name="status_memorandum"] option[value="andin"]`
                            ).prop('selected', true);
                            changeMemorandumStatusEdit('andin');
                        } else if (
                            (typeof data.asset.id_surat_memo_andin === 'undefined' ||
                                !data.asset.id_surat_memo_andin) &&
                            data.asset.no_memo_surat
                        ) {
                            changeMemorandumStatusEdit('manual');
                            form.find('input[name="no_memo_surat_manual"]').val(data.asset.no_memo_surat);
                            form.find(
                                `select[name="status_memorandum"] option[value="manual"]`
                            ).prop('selected', true);

                        } else {
                            changeMemorandumStatusEdit('tidak-ada');
                            form.find(
                                `select[name="status_memorandum"] option[value="tidak-ada"]`
                            ).prop('selected', true);
                        }
                        const baseUrl = "{{ config('app.url') }}";
                        form.find("#preview-file-image").attr('src', baseUrl +
                            "/assets/images/no_image.png");
                        if (data.asset.image[0] != null) {
                            form.find("#preview-file-image").attr('src', data.asset.image[0].link)
                        }

                        modal.on('shown.bs.modal', function() {
                            // setTimeout(() => {
                            //     generateGroupSelect2Edit();
                            //     generateSelect2LokasiEdit();
                            //     generateKelasAssetEdit();
                            //     generateSatuanAssetEdit();
                            // generateVendorAssetEdit();
                            //     generateOwnerAssetEdit();
                            //     generateMemorandumAndinSelect2();
                            // }, 2000);
                        }).modal('show');
                    }
                },
            })
        }

        const deleteAsset = (button) => {
            const form = $(button).closest('form');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.value) {
                    let formData = new FormData(form[0]);
                    let url = form.attr("action");
                    let method = form.attr("method");
                    let enctype = form.attr("enctype");
                    $.ajax({
                        url: url,
                        type: method,
                        enctype: enctype,
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function() {
                            $(".backdrop").show();
                        },
                        success: function(response) {
                            $(".backdrop").hide();
                            if (response.success) {
                                $("body").trigger("_EventAjaxSuccess", [
                                    form,
                                    response,
                                ]);
                            } else {
                                // console.log(response);
                                // showToaster(response.error, "Error");
                            }
                        },
                        error: function(response) {
                            $(".backdrop").hide();
                        },
                    });
                }
            });
        }

        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });

        $('#gambar_asset_edit').on('change', function() {
            const file = $(this)[0].files[0];
            console.log(file.name);
            $('#preview-file-text-edit').text(file.name);
        });

        $('#fileImport').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-excel-text').text(file.name);
        });

        const getNoUrutByKelompok = (select) => {
            var route;
            let id_asset = $(select).data('id_asset');
            if (id_asset != undefined) {
                route = "{{ route('admin.listing-asset.get-no-urut-by-kelompok-id', [':id', ':id_asset']) }}"
                route = route.replace(':id', $(select).val());
                route = route.replace(':id_asset', id_asset);
            }else{
                route = "{{ route('admin.listing-asset.get-no-urut-by-kelompok-id', [':id']) }}"
                route = route.replace(':id', $(select).val());
            }
            $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    $(".backdrop").show();
                },
                success: function(response){
                    $(".backdrop").hide();
                    if (response.success) {
                        console.log(response);
                        const modal = $(select).closest('.modal');
                        const form = modal.find('form');

                        form.find('input[name="no_urut"]').val(response.data);

                        generateKodeAsset($(select));
                    } else {
                        // console.log(response);
                        // showToaster(response.error, "Error");
                    }
                },
                error: function(response) {
                    $(".backdrop").hide();
                },
            });
        }


        const generateKodeAsset = (element) => {
            const form = $(element).closest('form');
            const no_urut = form.find('input[name="no_urut"]').val();
            const selectedOption = form.find('select[name="id_kategori_asset"]').select2('data')[0];

            if (selectedOption && selectedOption.text) {
                const kategori_asset = selectedOption.text;
                const matches = kategori_asset.match(/\(([^)]+)\)/);
                let valueInsideParentheses;
                if (matches) {
                    valueInsideParentheses = matches[1];
                }

                // Lanjutkan pemrosesan sesuai kebutuhan Anda
                const kode_asset = `${valueInsideParentheses}${no_urut !== "" ? no_urut : generateRandomString(5)}`;
                form.find('input[name="kode_asset"]').val(kode_asset);
            }
        }


        const generateRandomString = (num) => {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

            for (var i = 0; i < num; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
    </script>

    @include('pages.admin.listing-asset.components.script-js._script_modal_create')
    @include('pages.admin.listing-asset.components.script-js._script_modal_edit')
    @include('pages.admin.listing-asset.components.script-js._script_modal_filter')
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="hidden" id="jsonTempAsset" value="[]">
                <div class="d-flex align-items-center">
                    <div class="input-group mr-3" style="width: 250px;">
                        <input type="text" id="searchAsset" onkeyup="filterTableAsset()"
                            class="form-control form-control-sm" placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButtonAsset"
                                type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <button onclick="openModalByClass('modalFilterAsset')" class="btn btn-sm btn-secondary shadow-custom"
                        type="button"><i class="fas fa-sliders-h mr-2"></i>
                        Filter</button>
                    <button onclick="resetFilterData()" id="resetFilter"
                        class="btn btn-sm d-none btn-danger shadow-custom mr-2 ml-2" type="button"><i
                            class="fas fa-sync"></i>Reset</button>
                </div>
                <div class="d-flex align-items-center">
                    <div class="mb-2">
                        <form action="{{ route('admin.listing-asset.draft.delete-all-draft-asset') }}" class="form-confirm"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning shadow-custom mr-3"><i
                                    class="fas fa-question-circle mr-2"></i>Hapus Semua Aset</button>
                        </form>
                        <form action="{{ route('admin.listing-asset.draft.delete-many-asset') }}" class="form-confirm mt-2"
                            method="POST">
                            @csrf
                            <input type="hidden" id="jsonTempAssetDelete" name="json_id_asset_selected" value="[]">
                            <button type="submit" class="btn btn-sm btn-secondary shadow-custom mr-3"><i
                                    class="fas fa-trash mr-2"></i>Hapus Aset Terpilih</button>
                        </form>
                    </div>
                    <div class="mb-2">
                        <form action="{{ route('admin.listing-asset.draft.publish-all-draft-asset') }}"
                            class="form-confirm" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning shadow-custom mr-3"><i
                                    class="fas fa-question-circle mr-2"></i>Publish Semua Aset</button>
                        </form>
                        <form action="{{ route('admin.listing-asset.draft.publish-many-asset') }}"
                            class="form-confirm mt-2" method="POST">
                            @csrf
                            <input type="hidden" id="jsonTempAssetPublish" name="json_id_asset_selected" value="[]">
                            <button type="submit" class="btn btn-sm btn-secondary shadow-custom mr-3"><i
                                    class="fas fa-info-circle mr-2"></i>Publish Aset Terpilih</button>
                        </form>
                    </div>
                    <button onclick="openModalByClass('modalImportAsset')" class="btn btn-success shadow-custom btn-sm mr-2"
                        type="button"><i class="fa fa-file"></i>
                        Import Data</button>
                    <!-- <button onclick="openModalByClass('modalCreateAsset')" class="btn btn-primary shadow-custom btn-sm"
                        type="button"><i class="fa fa-plus"></i> Add</button> -->
                    <a href="{{route('admin.listing-asset.draft.add')}}" class="btn btn-primary shadow-custom btn-sm"
                        type="button"><i class="fa fa-plus"></i>Add</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="colTable">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><strong class="text-primary">Data Asset</strong></h5>
                        <h5 class="text-primary"><strong id="totalFilterAktif">Total 0</strong></h5>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped table-hover" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50">#</th>
                                    <th width="100px">Aksi</th>
                                    <th width="150px">Kode</th>
                                    <th width="200px">Deskripsi</th>
                                    <th width="200px">Tipe</th>
                                    <th width="200px">Barang IT</th>
                                    <th width="150px">Asset Group</th>
                                    <th width="150px">Jenis Asset</th>
                                    <th width="180px">Status Kondisi</th>
                                    <th width="100px">Tgl. Perolehan</th>
                                    <th width="150px">Nilai Perolehan</th>
                                    <th width="100px">Tgl. Pelunasan</th>
                                    <th width="150px">Lokasi</th>
                                    <th width="150px">Ownership</th>
                                    <th width="150px">Register Oleh</th>
                                    <th width="150px">Satuan</th>
                                    <th width="150px">Vendor</th>
                                    <th width="150px">Last Update</th>
                                    <th width="150px">Fungsi</th>
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
    @include('pages.admin.listing-asset.components.modal._modal_create')
    @include('pages.admin.listing-asset.components.modal._modal_edit_draft')
    @include('pages.admin.listing-asset.components.modal._modal_import')
    @include('pages.admin.listing-asset.components.modal._modal_filter')
    @include('pages.admin.listing-asset.components.modal._modal_search_asset')
@endsection
