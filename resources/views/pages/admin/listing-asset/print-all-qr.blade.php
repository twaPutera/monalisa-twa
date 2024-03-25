<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print All Qr</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link href="{{ asset('assets/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link href="{{ asset('assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.min.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        @media print {
        body {
            margin: 0 !important; /* Menghilangkan margin default */
            }
        }

        .page-break {
            page-break-after: always;
        }

        @print {
            .page-break {
                page-break-after: always;
            }

            @page {
                size: A4;
                margin-left: 0px;
                margin-right: 0px;
                margin-top: 0px;
                margin-bottom: 0px;
            }

            .no-print {
                display: none;
            }
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body style="background: white; padding: 20px;">
    <div class="row">
        <div class="col-md-12 mb-3 no-print">
            <div class="d-flex align-items-center justify-content-between">
                <nav aria-label="Page navigation example">
                    @if ($assets->lastPage() > 1)
                        <ul class="pagination">
                            <!-- Tautan Halaman Sebelumnya -->
                            <li class="page-item" <?php if ($assets->currentPage() == 1): ?> style="display: none;" <?php endif; ?>>
                                @php
                                    $params = array_merge(request()->all(), ['page' => $assets->currentPage() - 1]);
                                    $queryString = http_build_query($params);
                                @endphp
                                <a class="page-link" href="{{ url()->current() . '?' . $queryString }}">Previous</a>
                            </li>

                            <!-- Tautan Halaman -->
                            @for ($i = $assets->currentPage() < 3 ? 1 : $assets->currentPage() - 2; $i <= ($assets->currentPage() > $assets->lastPage() - 3 ? $assets->lastPage() : $assets->currentPage() + 3); $i++)
                                <li class="page-item <?php if ($assets->currentPage() == $i):?> active <?php endif; ?>">
                                    @php
                                        $params = array_merge(request()->all(), ['page' => $i]);
                                        $queryString = http_build_query($params);
                                    @endphp
                                    <a class="page-link"
                                        href="{{ url()->current() . '?' . $queryString }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <!-- Tautan Halaman Berikutnya -->
                            <li class="page-item" <?php if ($assets->currentPage() == $assets->lastPage()): ?> style="display: none;" <?php endif; ?>>
                                @php
                                    $params = array_merge(request()->all(), ['page' => $assets->currentPage() + 1]);
                                    $queryString = http_build_query($params);
                                @endphp
                                <a class="page-link" href="{{ url()->current() . '?' . $queryString }}">Next</a>
                            </li>
                        </ul>
                    @endif


                </nav>
                <form action="" method="GET" class="d-flex align-items-center">
                    <button onclick="openModalByClass('modalFilterAsset')" class="btn btn-info mr-2 shadow-custom"
                        type="button">Filter</button>
                    <button type="button" onclick="printQr()" class="btn btn-success">Print QR</button>
                </form>
            </div>
        </div>
        @foreach ($assets as $item)
            <div class="col-md-3 border border-dark p-2 @if ($loop->iteration % 16 == 0) page-break @endif">
                <div class="text-center">
                    <!-- <img src="{{ route('admin.listing-asset.preview-qr') . '?filename=' . $item->qr_code }}"
                        class="my-3 mx-3" width="90%" alt=""> -->
                        <img src="https://monalisa.universitaspertamina.ac.id/admin/listing-asset/preview-qr?filename=qr-asset-25200014.png"
                        class="my-3 mx-3" width="90%" alt="">
                </div>
                <div class="mt-3 text-center">
                    <h5>{{ $item->kode_asset }}</h5>
                    <h5>{{ $item->deskripsi }}</h5>
                    <a href="{{ route('admin.listing-asset.download-qr') . '?filename=' . $item->qr_code }}"
                        target="_blank" download class="btn btn-primary shadow-custom btn-sm no-print"><i
                            class="fa fa-download"></i> Unduh QR</a>
                </div>
            </div>
        @endforeach
    </div>
    <div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Filter Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="">
                    <div class="modal-body row">
                        <div class="form-group col-md-6 col-12">
                            <label for="">Limit</label>
                            <input type="number" name="limit" placeholder="Limit"
                                value="{{ isset(request()->limit) ? request()->limit : 50 }}" class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Nama Asset / Kode Asset</label>
                            <input type="text" id="searchAsset" name="deskripsi"
                                value="{{ isset(request()->deskripsi) ? request()->deskripsi : '' }}"
                                class="form-control form-control-sm" placeholder="Search for...">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Jenis Asset</label>
                            <select name="id_kategori_asset" class="form-control" id="kategoriAssetFilter">

                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Satuan</label>
                            <select name="id_satuan_asset" class="form-control" id="satuanAssetFilter">

                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Vendor</label>
                            <select name="id_vendor" class="form-control" id="vendorAssetFilter">

                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Lokasi</label>
                            <select name="id_lokasi" id="" class="form-control select2Lokasi">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Jenis</label>
                            <select name="is_sparepart" class="form-control" id="isSparepartFilter">
                                <option value="">Semua Jenis</option>
                                <option value="0">Asset</option>
                                <option value="1">Sparepart</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Penghapusan Asset</label>
                            <select name="is_pemutihan" class="form-control" id="isPemutihanFilter">
                                <option value="all">Semua Asset</option>
                                <option value="0" selected>Asset Yang Tidak Dalam Penghapusan</option>
                                <option value="1">Asset Yang Dalam Penghapusan</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">Tanggal Perolehan</label>
                            <div class="d-flex align-items-center" style="gap: 10px">
                                <input type="text" readonly placeholder="Awal" name="tgl_perolehan_awal"
                                    class="form-control datepicker w-50" id="">
                                <input type="text" readonly placeholder="Akhir" name="tgl_perolehan_akhir"
                                    class="form-control datepicker w-50" id="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/general/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        const printQr = () => {
            window.print();
        }

        const openModalByClass = (className) => {
            $(`.${className}`).modal('show');
        }

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
            });
            // select2('val', $('#lokasiParentId').val());
        }

        $(document).ready(function() {
            getDataOptionSelect();
            generateSelect2Lokasi();

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
    @include('pages.admin.listing-asset.components.script-js._script_modal_filter')
</body>

</html>
