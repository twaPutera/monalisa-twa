@extends('layouts.user.master')
@section('page-title', 'Dashboard')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataAssetOwner();
            getSummaryData();
        });
        const getAllDataAssetOwner = () => {
            $.ajax({
                url: '{{ route('user.asset-data.get-all-data-asset-by-user') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function(index, value) {
                                $('#assetContainer').append(generateTemplateAsset(value));
                            });
                        }
                        $('#countAsset').text(response.data.length);
                    }
                }
            })
        }
        const generateTemplateAsset = (data) => {
            return `
                <a href="${data.link_detail}" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
                    <div class="d-flex align-items-center" style="width: 60%;">
                        <div style="width: 50px;">
                            <div class="icon-wrapper pt-1 bg-primary rounded-circle text-center" style="width: 40px; height: 40px;">
                                <ion-icon name="checkmark-circle" style="font-size: 22px;"></ion-icon>
                            </div>
                        </div>
                        <div class="ms-1" style="width: calc(100% - 50px);">
                            <p class="text-dark mb-0 asset-deskripsi">${data.deskripsi}</p>
                            <p class="text-primary mb-0 asset-deskripsi"><i>${data.kategori_asset ? data.kategori_asset.group_kategori_asset.nama_group : 'Not Found'}, ${data.kategori_asset ? data.kategori_asset.nama_kategori : "Not Found"}</i></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                        <div class="me-1 text-end">
                            <p class="text-grey mb-0 text-end">${data.status_diterima}</p>
                            <span class="text-grey text-end">${data.tanggal_diterima}</span>
                        </div>
                        <div class="mb-0 text-grey text-end" style="font-size: 20px !important;">
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </div>
                    </div>
                </a>
            `;
        }
        const getSummaryData = () => {
            $.ajax({
                url: '{{ route('user.get-summary-dashboard') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    created_by: '{{ $user->guid ?? $user->id }}',
                },
                success: function(response) {
                    if (response.success) {
                        $('#totalAduan').text(response.data.total_aduan);
                        $('#totalApproval').text(response.data.total_approval);
                        $('#totalPeminjaman').text(response.data.total_peminjaman);
                    }
                },
            })
        }
    </script>
    <script>
        const getAllDataPeminjaman = (idContainer, status) => {
            $.ajax({
                url: '{{ route('user.asset-data.peminjaman.get-all-data') }}',
                data: {
                    guid_peminjam_asset: "{{ $user->guid ?? $user->id }}",
                    with: ['request_peminjaman_asset.kategori_asset'],
                    statusArray: status,
                    limit: 5,
                    orderby: {
                        field: 'tanggal_pengembalian',
                        sort: 'desc',
                    },
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function(index, value) {
                                $('#' + idContainer).append(generateTemplatePeminjaman(value));
                            })
                        } else {
                            $('#' + idContainer).append(`
                                <div class="section text-center mt-2">
                                    <h4 class="text-grey">Tidak Ada Data</h4>
                                </div>
                            `);
                        }
                    }
                }
            })
        }

        const generateTemplatePeminjaman = (data) => {
            return `
                <a href="#" data-link_detail="${data.link_detail}" data-link_perpanjangan="${data.link_perpanjangan}" onclick="showDetailPeminjaman(this)" data-bs-toggle="modal" data-bs-target="#ModalBasic" class="mb-2 bg-white px-2 py-2 d-block border-radius-sm border border-primary">
                    <p class="text-dark mb-0 asset-deskripsi">${data.code}</p>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="width: 60%;">
                            <div class="" style="">
                                <p class="text-primary mb-0 asset-deskripsi"><i>${data.status === 'pending' ? "Menunggu" : data.status === 'dipinjam' ? 'Dipinjam' : data.status === 'duedate' ? "Terlambat" : "Selesai"}</i></p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                            <div class="me-1 text-end">
                                <span class="text-grey text-end">${data.tanggal_pengembalian}</span>
                            </div>
                            <div class="mb-0 text-grey text-end" style="font-size: 20px !important;">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </div>
                        </div>
                    </div>
                </a>
            `;
        }

        const showDetailPeminjaman = (element) => {
            const url_detail = $(element).data('link_detail');
            const url_perpanjangan = $(element).data('link_perpanjangan');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $(".loadingSpiner").show();
                },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        const array_asset = data.detail_peminjaman_asset.map((item) => (
                            JSON.parse(item.json_asset_data)
                        ));
                        $('#tanggalPeminjaman').val(data.tanggal_peminjaman);
                        $('.tanggalPengembalian').val(data.tanggal_pengembalian);
                        $('#statusPeminjaman').html(generateStatusPeminjaman(data.status));
                        $('.containerDetailPeminjaman').empty();
                        $(data.request_peminjaman_asset).each(function(index, value) {
                            const array_asset_item = array_asset.filter((item) => (
                                item.id_kategori_asset === value.id_kategori_asset
                            ));
                            $('.containerDetailPeminjaman').append(generateDetailPeminjaman(value
                                .kategori_asset.nama_kategori, array_asset_item));
                        })
                        $('#alasanPeminjaman').text(data.alasan_peminjaman);

                        $('.containerPerpanjangan').empty();

                        if (data.perpanjangan_peminjaman_asset.length > 0) {
                            $(data.perpanjangan_peminjaman_asset).each(function(index, value) {
                                $('.containerPerpanjangan').append(generateListHistoryPerpanjangan(
                                    value));
                            })
                        }

                        if (data.status == 'dipinjam' || data.status == 'duedate') {
                            const data_perpanjangan = data.perpanjangan_peminjaman_asset.filter((item) => (
                                item.status === 'pending'
                            ));
                            if (data_perpanjangan.length < 1) {
                                $('#formPerpanjangan').attr('action', url_perpanjangan);
                                $('#btnShowPerpanjangan').show();
                            }
                        } else if (data.status == 'selesai') {
                            $('#formPerpanjangan').attr('action', "");
                            $('#btnShowPerpanjangan').hide();
                            $('#keteranganPengembalian').text(data.keterangan_pengembalian);
                            $('#rating').text(data.rating);
                        } else {
                            $('#formPerpanjangan').attr('action', "");
                            $('#btnShowPerpanjangan').hide();
                        }

                        $(".loadingSpiner").hide();
                        $('#modalDetailPeminjaman').modal('show');
                    }
                }
            })
        }

        const generateStatusPeminjaman = (status) => {
            let template = '';
            if (status == 'pending') {
                template = '<span class="badge badge-warning">Menunggu Approval</span>';
            } else if (status == 'dipinjam') {
                template = '<span class="badge badge-primary">Sedang Dipinjam</span>';
            } else if (status == 'duedate') {
                template = '<span class="badge badge-warning">Terlambat</span>';
            } else if (status == 'selesai') {
                template = '<span class="badge badge-success">Selesai</span>';
            } else if (status == 'ditolak') {
                template = '<span class="badge badge-danger">Ditolak</span>';
            } else if (status == 'disetujui') {
                template = '<span class="badge badge-success">Disetujui</span>';
            }

            return template;
        }

        const generateDetailPeminjaman = (nama_kategori, data_asset) => {
            return `
                    <div class="mt-2 border-radius-sm border p-1">
                        <p class="text-dark"><strong>${nama_kategori}</strong></p>
                        <ul class="listview simple-listview px-0">
                            ${generateListDetailPeminjaman(data_asset)}
                        </ul>
                    </div>
            `;
        }

        const generateListDetailPeminjaman = (data_asset) => {
            let element = '';
            $(data_asset).each(function(index, value) {
                element += `<li>${value.deskripsi}</li>`;
            })
            return element;
        }

        let showPerpanjangan = true;

        const showHideFormPerpanjangan = (element) => {
            if (showPerpanjangan) {
                $(element).text('Batal').removeClass('btn-warning').addClass('btn-danger');
                $('.containerPerpanjanganForm').show();
                $('#btnSubmitPerpanjangan').show();
                showPerpanjangan = false;
            } else {
                $(element).text('Ajukan Perpanjangan').removeClass('btn-danger').addClass('btn-warning');
                $('.containerPerpanjanganForm').hide();
                $('#btnSubmitPerpanjangan').hide();
                showPerpanjangan = true;
            }
        }

        const generateListHistoryPerpanjangan = (data) => {
            return `
                <div class="py-2 px-2 border border-primary border-radius-sm">
                    <div class="border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-0"><strong>Tanggal Perpanjangan</strong></p>
                            <p class="mb-0">${data.tanggal_expired_perpanjangan}</p>
                        </div>
                        ${generateStatusPeminjaman(data.status)}
                    </div>
                    <p class="mb-0">${data.alasan_perpanjangan}</p>
                </div>
            `;
        }

        const submitForm = () => {
            $('.form-submit').submit();
        }

        $(document).ready(function() {
            getAllDataPeminjaman('dipinjamContainer', ['dipinjam', 'duedate', 'pending']);
        });

        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);
                $('#modalDetailPengaduan').modal('hide');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            if (!errors.success && errors.message) {
                dialogDanger(errors.message, errors.error);
            }
            for (let key in errors) {
                let array_error_key = key.split('.');
                if (array_error_key.length < 2) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                } else {
                    let new_key = `${array_error_key[0]}[${array_error_key[1]}][${array_error_key[2]}]`;
                    let element = formElement.find(`[name="${new_key}"]`);
                    $(element).addClass('is-invalid');
                    $(`#errorJumlah-${array_error_key[1]}`).text(errors[key][0]).show();
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            getDataPengaduan('pengaduanContainer', ['diproses', 'dilaporkan']);
        });
        const getDataPengaduan = (idContainer, status) => {
            $.ajax({
                url: "{{ route('user.pengaduan.get-all-data') }}",
                data: {
                    created_by: "{{ $user->guid ?? $user->id }}",
                    with: ['asset_data', 'asset_data.lokasi', 'lokasi'],
                    arrayStatus: status,
                    limit: 5,
                    orderby: {
                        field: 'created_at',
                        sort: 'asc',
                    },
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function(index, value) {
                                $('#' + idContainer).append(generateTemplatePengaduan(value));
                            })
                        } else {
                            $('#' + idContainer).append(`
                                <div class="section text-center mt-2">
                                    <h4 class="text-grey">Tidak Ada Data</h4>
                                </div>
                            `);
                        }
                    }
                }
            })
        }
        const emptyFieldBeforeAppend = () => {
            $('#logContainer').empty();
            $('#namaAsset').empty();
            $('#lokasiAsset').empty();
            $('#kelompokAsset').empty();
            $('#jenisAsset').empty();
            $('#statusTerakhir').empty();
            $('#statusPemutihan').empty();
            $('#prioritas').empty();
            $('#statusPengaduan').empty();
            $('#catatanPengaduan').empty();
            $('#catatanResponPengaduan').empty();
        }

        const showDetailPengaduan = (element) => {
            const url_detail = $(element).data('link_detail');
            $.ajax({
                url: url_detail,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $(".loadingSpiner").show();
                },
                success: function(response) {
                    if (response.success) {
                        emptyFieldBeforeAppend();
                        const data = response.data;
                        getAllDataLogPengaduan("logContainer", data.id);
                        if (data.asset_data != null) {

                            $('#namaAsset').append(data.asset_data.deskripsi);
                            if (data.asset_data.id_lokasi != null) {
                                $('#lokasiAsset').append(data.asset_data.lokasi.nama_lokasi);
                            } else {
                                $('#lokasiAsset').append("Tidak Ada Lokasi");
                            }

                            if (data.asset_data.id_kategori_asset != null) {
                                if (data.asset_data.kategori_asset != null) {
                                    $('#kelompokAsset').append(data.asset_data.kategori_asset
                                        .group_kategori_asset
                                        .nama_group);
                                    $('#jenisAsset').append(data.asset_data.kategori_asset.nama_kategori);
                                } else {
                                    $('#kelompokAsset').append('-');
                                    $('#jenisAsset').append('-');
                                }
                            } else {
                                $('#kelompokAsset').append("Tidak Ada Kelompok Asset");
                                $('#jenisAsset').append("Tidak Ada Jenis Asset");
                            }
                            if (data.asset_data.status_kondisi == 'bagus') {
                                var kondisi = '<span class="badge badge-success">Baik</span>';
                            } else if (data.asset_data.status_kondisi == 'rusak') {
                                var kondisi = '<span class="badge badge-danger">Rusak</span>';
                            } else if (data.asset_data.status_kondisi == 'maintenance') {
                                var kondisi = '<span class="badge badge-warning">Maintenance</span>';
                            } else if (data.asset_data.status_kondisi == 'tidak-lengkap') {
                                var kondisi = '<span class="badge badge-dark">Tidak Lengkap</span>';
                            } else if (data.asset_data.status_kondisi == 'pengembangan') {
                                var kondisi = '<span class="badge badge-info">Pengembangan</span>';
                            }
                            $('#statusTerakhir').append(kondisi);

                            if (data.asset_data.is_pemutihan == 0) {
                                var pemutihan =
                                    '<span class="badge badge-success px-3">Tidak Dalam Penghapusan Asset</span>';
                            } else if (data.asset_data.is_pemutihan == 1) {
                                var pemutihan = '<span class="badge badge-danger px-3">Dalam Penghapusan Asset</span>';
                            }
                            $('#statusPemutihan').append(pemutihan);
                            $('#tanggalPengaduan').val(data.tanggal_pengaduan);
                        } else {
                            var pemutihan = '<span class="badge badge-secondary px-3">Tidak Ada</span>';
                            var kondisi = '<span class="badge badge-secondary">Tidak Ada</span>';
                            $('#lokasiAsset').append(data.lokasi.nama_lokasi);
                            $('#namaAsset').append("Tidak Ada Asset");
                            $('#kelompokAsset').append("Tidak Ada Kelompok Asset");
                            $('#jenisAsset').append("Tidak Ada Jenis Asset");
                            $('#statusTerakhir').append(kondisi);
                            $('#statusPemutihan').append(pemutihan);
                            $('#tanggalPengaduan').val('Tidak Ada');
                        }

                        if (data.prioritas == 10) {
                            var prioritas = '<span class="badge badge-danger px-3">High</span>';
                        } else if (data.prioritas == 5) {
                            var prioritas = '<span class="badge badge-warning px-3">Medium</span>';
                        } else if (data.prioritas == 1) {
                            var prioritas = '<span class="badge badge-info px-3">Low</span>';
                        } else {
                            var prioritas = '<span class="badge badge-secondary px-3">Tidak Ada</span>';
                        }
                        $('#prioritas').append(prioritas);

                        if (data.status_pengaduan == "dilaporkan") {
                            var status_pengaduan =
                                '<span class="badge badge-danger px-3">Dilaporkan</span>';
                        } else if (data.status_pengaduan == "diproses") {
                            var status_pengaduan = '<span class="badge badge-warning px-3">Diproses</span>';
                        } else if (data.status_pengaduan == "selesai") {
                            var status_pengaduan = '<span class="badge badge-success px-3">Selesai</span>';
                        } else {
                            var status_pengaduan =
                                '<span class="badge badge-secondary px-3">Tidak Ada</span>';
                        }
                        $('#statusPengaduan').append(status_pengaduan);
                        $('#catatanPengaduan').append(data.catatan_pengaduan);

                        if (data.image[0] != null) {
                            var url_gambar_saya =
                                "{{ route('user.pengaduan.download-gambar') }}?filename=" +
                                data.image[0].path + "&status=request";
                            $('#gambarSaya').removeClass('d-none');
                            $('#urlGambarSaya').attr('href', url_gambar_saya)
                        } else {
                            $('#gambarSaya').addClass('d-none');
                        }

                        if (data.status_pengaduan != "dilaporkan") {
                            $('#responPengaduan').removeClass('d-none');
                            $('#catatanResponPengaduan').append(data.catatan_admin);
                            if (data.image[1] != null) {
                                var url_gambar_respon =
                                    "{{ route('user.pengaduan.download-gambar') }}?filename=" +
                                    data.image[1].path + "&status=response";
                                $('#gambarRespon').removeClass('d-none');
                                $('#urlGambarRespon').attr('href', url_gambar_respon)
                            } else {
                                $('#gambarRespon').addClass('d-none');
                            }
                        } else {
                            $('#responPengaduan').addClass('d-none');
                        }

                        if (data.status_pengaduan == "dilaporkan") {
                            $('#editOrDeleteButton').removeClass('d-none')
                            var delete_link = "{{ route('user.pengaduan.destroy', '') }}" + '/' + data.id;
                            var edit_link = "{{ route('user.pengaduan.edit', '') }}" + '/' + data.id;
                            $('#deletePengaduanButton').attr('action', delete_link);
                            $('#editPengaduanButton').attr('href', edit_link);
                        } else {
                            $('#editOrDeleteButton').addClass('d-none');
                        }
                        $(".loadingSpiner").hide();
                        $('#modalDetailPengaduan').modal('show');
                    }
                }
            })
        }
        const getAllDataLogPengaduan = (idContainer, idPengaduan) => {
            $.ajax({
                url: '{{ route('user.pengaduan.get-all-data-log') }}',
                data: {
                    with: ['pengaduan'],
                    id_pengaduan: idPengaduan
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function(index, value) {
                                $('#' + idContainer).append(generateTemplateLogPengaduan(value));
                            })
                        } else {
                            $('#' + idContainer).append(`
                                <div class="section text-center mt-2">
                                    <h4 class="text-grey">Tidak Ada Data</h4>
                                </div>
                            `);
                        }
                    }
                }
            })
        }
        const generateStatusPengaduan = (status) => {
            let template = '';
            if (status == 'dilaporkan') {
                template = '<span class="badge badge-warning">Dilaporkan</span>';
            } else if (status == 'diproses') {
                template = '<span class="badge badge-primary">Diproses</span>';
            } else if (status == 'selesai') {
                template = '<span class="badge badge-success">Selesai</span>';
            }

            return template;

        }

        const generateTemplateLogPengaduan = (data) => {
            return `
            <div class="py-2 px-2 border mb-2 border-primary border-radius-sm">
                <div class="border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-dark mb-0"><strong>Tanggal Log</strong></p>
                        <p class="mb-0">${data.tanggal_log}</p>
                       </div>
                        ${generateStatusPengaduan(data.status)}
                </div>
                    <p class="mb-0">${data.message_log}</p>
            </div>
            `
        }
        const generateTemplatePengaduan = (data) => {
            return `
                <a href="#" data-link_detail="${data.link_detail}" onclick="showDetailPengaduan(this)" class="mb-2 bg-white px-2 py-2 d-block border-radius-sm border border-primary">
                    <p class="text-dark mb-0 asset-deskripsi">${data.asset_data != null ? data.asset_data.deskripsi : 'Pengaduan'} - ${ data.asset_data != null ? (data.asset_data.id_lokasi != null ? data.asset_data.lokasi.nama_lokasi : 'Tidak Ada Lokasi') : data.lokasi.nama_lokasi} </p>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="width: 60%;">
                            <div class="" style="">
                                <p class="text-primary mb-0 asset-deskripsi" style="text-transform:capitalize"><i>${data.status_pengaduan}</i></p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                            <div class="me-1 text-end">
                                <span class="text-grey text-end">${data.tanggal_pengaduan}</span>
                            </div>
                            <div class="mb-0 text-grey text-end" style="font-size: 20px !important;">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </div>
                        </div>
                    </div>
                </a>
            `;
        }
    </script>
@endsection
@section('content')
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <!-- Balance -->
            <div class="balance">
                <div class="left">
                    <span class="title text-primary">Selamat Datang</span>
                    <h1 class="text-muted" style="font-size: 20px;">{{ $user->name }}</h1>
                    <span class="text-muted">{!! ucWords(App\Helpers\CutText::cutUnderscore($user->role)) !!}</span>
                </div>
                <div class="right">
                    <img alt="Logo" src="{{ asset('assets/images/logo-Press-103x75.png') }}"
                        class="kt-header__brand-logo-default" width="80px" />
                </div>
            </div>
            <!-- * Balance -->
            <!-- Wallet Footer -->
            <div class="wallet-footer justify-content-between">
                <div class="item">
                    <a href="{{ route('user.approval.index') }}">
                        <div class="icon-wrapper bg-primary" id="totalApproval">
                            0
                        </div>
                        <strong>Pemindahan Aset</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('user.pengaduan.index') }}">
                        <div class="icon-wrapper bg-danger" id="totalAduan">
                            0
                        </div>
                        <strong>Aduan</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('user.asset-data.peminjaman.index') }}">
                        <div class="icon-wrapper bg-info" id="totalPeminjaman">
                            0
                        </div>
                        <strong>Peminjaman</strong>
                    </a>
                </div>
            </div>
            <!-- * Wallet Footer -->
        </div>
    </div>
    <div class="section mt-2">
        <div class="d-flex justify-content-between">
            <h2 class="text-grey"><strong>Pengaduan</strong></h2>
            {{-- <h2 class="text-grey"><strong id="countPengaduan">(2)</strong></h2> --}}
        </div>
    </div>
    <div class="section mt-2">
        <div class="card p-1 bg-light-grey border-radius-sm">
            <div class="card-body p-1 bg-light-grey" id="pengaduanContainer">

                {{-- <a href="#" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper pt-1 bg-danger rounded-circle text-center" style="width: 40px; height: 40px;">
                        <ion-icon name="remove-circle-outline" style="font-size: 23px;"></ion-icon>
                    </div>
                    <div class="ms-2">
                        <p class="text-dark mb-0">Lenovo Yoga</p>
                        <span class="text-primary"><i>Elektronik, Laptop</i></span>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <div class="me-2">
                        <p class="text-grey mb-0 text-end">Belum Diterima</p>
                        <p class="text-grey mb-0 text-end">-</p>
                    </div>
                    <div class="mb-0 text-grey text-end" style="font-size: 27px !important;">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </div>
            </a> --}}
            </div>
        </div>
    </div>
    <div class="section mt-2">
        <div class="d-flex justify-content-start">
            <h2 class="text-grey"><strong>Peminjaman Deadline Terdekat</strong></h2>
            {{-- <h2 class="text-grey"><strong id="countPeminjaman">(2)</strong></h2> --}}
        </div>
    </div>
    <div class="section mt-2">
        <div class="" id="dipinjamContainer"></div>
    </div>
    @include('pages.user.asset.peminjaman._modal_detail')
    @include('pages.user.pengaduan._modal_detail')
@endsection
