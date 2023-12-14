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
            width: 250% !important;
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
                $('#lokasiFilterAktif').text(data.node.text);
                $('#lokasiParentId').val(data.selected[0]);
                table.DataTable().ajax.reload();
            });

            $('#searchButton').on('click', function() {
                $('#lokasiTree').jstree('search', $('#searchTree').val());
            });

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
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
                        d.is_draft = '0';
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
                            return type == '1' ? 'Departement IT' : 'Departement Aset';
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
                        data: 'tanggal_perolehan'
                    },
                    {
                        data: 'nilai_perolehan'
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
                    }
                ],
                columnDefs: [{
                        targets: 9,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    },
                    {
                        targets: 17,
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        },
                    },
                    {
                        targets: 8,
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
                        targets: 10,
                        render: function(data, type, full, meta) {
                            return formatNumber(data);
                        }
                    }
                ],
                createdRow: function(row, data, index) {
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

                    $(row).attr('data-id', data.id).addClass('row-asset').attr("style", "cursor: pointer;");

                    $(row).on('click', function() {
                        let id = $(this).data('id');
                        showAsset(data.action);
                    });
                },
                footerCallback: function(row, data, start, end, display) {
                    //
                },
                drawCallback: function() {
                    var api = this.api();
                    // var num_rows = api.page.info().recordsTotal;
                    var records_displayed = api.page.info().recordsDisplay;
                    let target = $('#totalFilterAktif');
                    target.empty();
                    target.append("Total " + records_displayed);
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
        const showAsset = (button) => {
            const url = $(button).data('url_detail');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $(".backdrop").show();
                },
                success: function(response) {
                    $(".backdrop").hide();
                    if (response.success) {
                        const asset = response.data.asset;
                        const opname = response.data.asset.log_asset_opname[0];
                        if (asset.status_kondisi == 'rusak') {
                            var kondisi =
                                `<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Rusak</span>`;
                        } else if (asset.status_kondisi == 'maintenance') {
                            var kondisi =
                                `<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Maintenance</span>`;
                        } else if (asset.status_kondisi == 'tidak-lengkap') {
                            var kondisi =
                                `<span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Lengkap</span>`;
                        } else if (asset.status_kondisi == 'pengembangan') {
                            var kondisi =
                                `<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">Pengembangan</span>`;
                        } else if (asset.status_kondisi == 'tidak-ditemukan') {
                            var kondisi =
                                `<span class="kt-badge kt-badge--dark kt-badge--inline kt-badge--pill kt-badge--rounded">Tidak Ditemukan</span>`;
                        } else {
                            var kondisi =
                                `<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>`;
                        }


                        // if (asset.is_pemutihan == 0) {
                        //     var pemutihan =
                        //         `<h6 class="text-center text-danger" style="font-size: 24px"><i
                    //             class="fas fa-times-circle"></i></h6>`;
                        // } else if (asset.is_pemutihan == 1) {
                        //     var pemutihan =
                        //         `<h6 class="text-center text-success" style="font-size: 24px"><i
                    //             class="fas fa-check-circle"></i></h6>`;
                        // }

                        if (asset.is_pinjam == 0) {
                            var pinjam =
                                `<h6 class="text-center text-danger" style="font-size: 24px"><i
                                    class="fas fa-times-circle"></i></h6>`;
                        } else if (asset.is_pinjam == 1) {
                            var pinjam =
                                `<h6 class="text-center text-success" style="font-size: 24px"><i
                                    class="fas fa-check-circle"></i></h6>`;
                        } else {
                            var pinjam =
                                `<h6 class="text-center text-secondary" style="font-size: 24px"><i
                                    class="fas fa-question-circle"></i></h6>`;
                        }

                        if (asset.peminjam != null) {
                            $('#peminjamAsset').html(asset.peminjam);
                            $('#assetPinjam').html(
                                '<h6 class="text-center text-success" style="font-size: 24px"><i class="fas fa-check-circle"></i></h6>'
                            );
                        } else {
                            $('#peminjamAsset').html('Tidak Ada');
                            $('#assetPinjam').html(
                                '<h6 class="text-center text-danger" style="font-size: 24px"><i class="fas fa-times-circle"></i></h6>'
                            );
                        }

                        $('#assetNamePreview').text(asset.deskripsi);
                        $('#assetKondisi').empty();
                        $('#assetKondisi').append(kondisi);
                        // $('#assetPemutihan').empty();
                        // $('#assetPemutihan').append(pemutihan);
                        // $('#assetPinjam').empty();
                        // $('#assetPinjam').append(pinjam);
                        $('#opnameCekBy').empty();
                        $('#opnameCekBy').append(asset.created_by_opname);
                        $('#catatanOpname').text(opname ? opname.keterangan : 'Tidak Ada');
                        $('#lastLogOpnameDate').text(opname ? formatDateIntoIndonesia(opname
                            .tanggal_opname) : 'Tidak Ada');
                        if (asset.image.length > 0) {
                            $('#imgPreviewAsset').attr('src', asset.image[0].link);
                        } else {
                            $('#imgPreviewAsset').attr('src',
                                'https://via.placeholder.com/400x250?text=Preview Image');
                        }
                        $('#linkDetailAsset').attr('href', asset.link_detail);
                    }
                },
                error: function(response) {
                    $(".backdrop").hide();
                    showToastError('Error', 'Terjadi kesalahan');
                }
            })
        }

        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });

        $('#fileImport').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-excel-text').text(file.name);
        });

        const generateLinkDownloadQr = () => {
            let url = "{{ route('admin.listing-asset.download-qr-all') }}?";

            let selects = ['id', 'qr_code'];
            let id_lokasi = $('#lokasiParentId').val();
            let id_satuan_asset = $('#satuanAssetFilter').val();
            let id_vendor = $('#vendorAssetFilter').val();
            let id_kategori_asset = $('#kategoriAssetFilter').val();
            let searchKeyword = $('#searchAsset').val();
            let is_sparepart = $('#isSparepartFilter').val();
            let is_pemutihan = '0';
            let is_draft = '0';

            if (id_lokasi != null) {
                url += `&id_lokasi=${id_lokasi}`;
            }

            if (id_satuan_asset != null) {
                url += `&id_satuan_asset=${id_satuan_asset}`;
            }

            if (id_vendor != null) {
                url += `&id_vendor=${id_vendor}`;
            }

            if (id_kategori_asset != null) {
                url += `&id_kategori_asset=${id_kategori_asset}`;
            }

            if (searchKeyword != null) {
                url += `&searchKeyword=${searchKeyword}`;
            }

            if (is_sparepart != null) {
                url += `&is_sparepart=${is_sparepart}`;
            }

            if (is_pemutihan != null) {
                url += `&is_pemutihan=${is_pemutihan}`;
            }

            if (is_draft != null) {
                url += `&is_draft=${is_draft}`;
            }

            selects.forEach((select) => {
                url += `&selects[]=${select}`;
            });

            // blank page for download
            window.open(url, '_blank');
        }

        let isTreeShown = false;
        const showTree = (table, detail, tree, sectionTable) => {
            const idTable = $('#' + table);
            const idDetail = $('#' + detail);
            const idTree = $('#' + tree);
            const idSection = $('#' + sectionTable);

            if (isTreeShown) {
                // Kembalikan ke tampilan sebelumnya
                idTable.removeClass('col-8').addClass('col-9');
                idDetail.removeClass('col-4').addClass('col-3');
                idTree.addClass('d-none');
                idSection.removeClass('col-md-10').addClass('col-md-12');
            } else {
                // Tampilkan tree
                idTable.removeClass('col-9').addClass('col-8');
                idDetail.removeClass('col-3').addClass('col-4');
                idTree.removeClass('d-none');
                idSection.removeClass('col-md-12').addClass('col-md-10');
            }

            // Toggle status
            isTreeShown = !isTreeShown;
        }
    </script>

    @include('pages.admin.listing-asset.components.script-js._script_modal_create')
    @include('pages.admin.listing-asset.components.script-js._script_modal_filter')
@endsection
@section('main-content')
    <input type="hidden" value="" id="lokasiParentId">
    <div class="row">
        <div class="col-md-2 col-12 d-none" id="colLokasi">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Tree Lokasii
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
        <div class="col-md-12 col-12" id="colAllTableDetail">
            <div class="d-flex justify-content-between align-items-center mb-3">
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
                        class="btn btn-sm d-none btn-danger shadow-custom ml-2" type="button"><i
                            class="fas fa-sync"></i>Reset</button>
                    <button onclick="showTree('colTable','colDetail','colLokasi','colAllTableDetail')" id="resetFilter"
                        class="btn btn-sm btn-primary shadow-custom mr-2 ml-2" type="button"><i
                            class="fas fa-project-diagram"></i>Tree Lokasi</button>
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.pemutihan-asset.asset.index') }}"
                        class="btn btn-danger shadow-custom btn-sm mr-2"><i class="fas fa-backspace"></i>
                        Penghapusan Asset</a>
                    <button type="button" onclick="generateLinkDownloadQr()"
                        class="btn btn-success shadow-custom btn-sm mr-2"><i class="fas fa-download"></i>
                        Download All QR</button>
                    <a href="{{ route('admin.listing-asset.draft.index') }}"
                        class="btn btn-primary shadow-custom btn-sm mr-2"><i class="fas fa-file"></i>
                        Draft Aset</a>
                    <a href="{{ route('admin.listing-asset.print-qr-all') }}" target="_blank"
                        class="btn btn-sm btn-info"><i class="fas fa-qrcode"></i> Print QR</a>
                    {{-- <button onclick="openModalByClass('modalImportAsset')" class="btn btn-success shadow-custom btn-sm mr-2"
                        type="button"><i class="fa fa-file"></i> Import CSV</button>
                    <button onclick="openModalByClass('modalCreateAsset')" class="btn btn-primary shadow-custom btn-sm"
                        type="button"><i class="fa fa-plus"></i> Add</button> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-9" id="colTable">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><strong class="text-primary">Data Asset</strong> <span class="text-gray"> -
                                Lokasi Asset (<span id="lokasiFilterAktif">Universitas Pertamina</span>)</span></h5>
                        <h5 class="text-primary"><strong id="totalFilterAktif">Total 0</strong></h5>
                    </div>
                    <div class="table-responsive custom-scroll">
                        <table class="table table-striped table-hover" id="datatableExample">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50px">Aksi</th>
                                    <th width="150px">Kode</th>
                                    <th width="200px">Deskripsi</th>
                                    <th width="200px">Tipe</th>
                                    <th width="200px">Kepemilikan</th>
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
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-3" id="colDetail">
                    <h5 class="text-primary mb-0 mb-3"><strong class="">Overview</strong></h5>
                    <div class="detail-asset-box">
                        <h5 class="title" id="assetNamePreview">ASSET NAME</h5>
                        <img id="imgPreviewAsset" src="https://via.placeholder.com/400x250?text=Preview Image"
                            alt="">
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Status Kondisi Asset</h6>
                            <div id="assetKondisi">
                                <h6 class="text-right">No Item Selected</h6>
                            </div>
                        </div>
                        {{-- <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Status Pemutihan</h6>
                            <div id="assetPemutihan">
                                <h6 class="text-right">No Item Selected</h6>
                            </div>
                        </div> --}}
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6 class="">Catatan</h6>
                            <h6 class="text-right" id="catatanOpname">No Item Selected</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Log Terakhir</h6>
                            <h6 class="text-right" id="lastLogOpnameDate">No Item Selected</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Dicek Oleh</h6>
                            <h6 class="text-right" id="opnameCekBy">No Item Selected</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-3 py-2 align-items-center border-bottom">
                            <h6 class="mb-0">Status Peminjaman</h6>
                            <div id="assetPinjam">
                                <h6 class="text-right">No Item Selected</h6>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-1 py-2 border-bottom">
                            <h6>Peminjam</h6>
                            <h6 class="text-right" id="peminjamAsset">No Item Selected</h6>
                        </div>
                        <div class="text-right">
                            <a href="#" class="text-primary" id="linkDetailAsset"><u>Lihat Detail</u></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.listing-asset.components.modal._modal_create')
    @include('pages.admin.listing-asset.components.modal._modal_import')
    @include('pages.admin.listing-asset.components.modal._modal_filter')
    @include('pages.admin.listing-asset.components.modal._modal_search_asset')
@endsection
