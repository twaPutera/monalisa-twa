@extends('layouts.user.master')
@section('page-title', 'Daftar Aduan')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataPeminjaman('pendingContainer', 'dilaporkan');
            getAllDataPeminjaman('dipinjamContainer', 'diproses');
            getAllDataPeminjaman('selesaiContainer', 'selesai');
        })
    </script>
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);
                $('#modalDetailPengaduan').modal('hide');
                setTimeout(() => {
                    window.location.href = '{{ route('user.pengaduan.index') }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            if (!errors.success) {
                changeTextToast('toastDanger', errors.message);
                toastbox('toastDanger', 2000)
            }
            for (let key in errors) {
                let element = formElement.find(`[name=${key}]`);
                clearValidation(element);
                showValidation(element, errors[key][0]);

            }
        });
    </script>

    <script>
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
                                $('#' + idContainer).append(generateTemplateLog(value));
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
        const generateStatusPeminjaman = (status) => {
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

        const getAllDataPeminjaman = (idContainer, status) => {
            $.ajax({
                url: '{{ route('user.pengaduan.get-all-data') }}',
                data: {
                    created_by: "{{ $user->guid ?? $user->id }}",
                    with: ['asset_data', 'asset_data.lokasi', 'lokasi'],
                    status_pengaduan: status,
                    global: true
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function(index, value) {
                                $('#' + idContainer).append(generateTemplateApproval(value));
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

        const generateTemplateLog = (data) => {
            return `
            <div class="py-2 px-2 border mb-2 border-primary border-radius-sm">
                <div class="border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-dark mb-0"><strong>Tanggal Log</strong></p>
                        <p class="mb-0">${data.tanggal_log}</p>
                       </div>
                        ${generateStatusPeminjaman(data.status)}
                </div>
                    <p class="mb-0">${data.message_log}</p>
            </div>
            `
        }



        const generateTemplateApproval = (data) => {
            return `
            <a href="#" data-link_detail="${data.link_detail}"  onclick="showDetailPeminjaman(this)" class="mb-2 bg-white px-2 py-2 d-block border-radius-sm border border-primary">
                <p class="text-dark mb-0 asset-deskripsi">${data.asset_data != null ? data.asset_data.deskripsi : 'Pengaduan'} - ${ data.asset_data != null ? (data.asset_data.lokasi != null ? data.asset_data.lokasi.nama_lokasi : 'Lokasi Asset Tidak Ada') : data.lokasi.nama_lokasi} </p>
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

        const showDetailPeminjaman = (element) => {
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
                            } else {
                                var kondisi = '<span class="badge badge-secondary">Tidak Ada</span>';
                            }
                            $('#statusTerakhir').append(kondisi);

                            if (data.asset_data.is_pemutihan == 0) {
                                var pemutihan =
                                    '<span class="badge badge-success px-3">Tidak Dalam Penghapusan Asset</span>';
                            } else if (data.asset_data.is_pemutihan == 1) {
                                var pemutihan = '<span class="badge badge-danger px-3">Dalam Penghapusan Asset</span>';
                            } else {
                                var pemutihan = '<span class="badge badge-secondary px-3">Tidak Ada</span>';
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
    </script>
@endsection
@section('content')
    <ul class="nav nav-tabs lined" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#overview2" role="tab" aria-selected="true">
                Dilaporkan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#cards2" role="tab" aria-selected="false">
                Sedang Diproses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#cards3" role="tab" aria-selected="false">
                Selesai
            </a>
        </li>
    </ul>
    <div class="tab-content mt-2">
        <div class="tab-pane fade active show" id="overview2" role="tabpanel">
            <div class="section">
                <div class="" id="pendingContainer"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="cards2" role="tabpanel">
            <div class="section">
                <div class="" id="dipinjamContainer"></div>
            </div>
        </div>
        <div class="tab-pane fade" id="cards3" role="tabpanel">
            <div class="section">
                <div class="" id="selesaiContainer"></div>
            </div>
        </div>
    </div>
    @include('pages.user.pengaduan._modal_detail')
@endsection
