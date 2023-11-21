@extends('layouts.user.master')
@section('page-title', 'Daftar Peminjaman')
@section('custom-css')
    <style>
        .containerPerpanjangan {
            /* transition: top 300ms cubic-bezier(0.17, 0.04, 0.03, 0.94); */
        }
    </style>

@endsection
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataPeminjaman('pendingContainer', ['pending']);
            getAllDataPeminjaman('dipinjamContainer', ['dipinjam', 'duedate', 'ditolak']);
            getAllDataPeminjaman('selesaiContainer', ['selesai']);

            @if (isset(request()->peminjaman_asset_id))
                $('#notifikasiId').data('link_detail', "{{ route('user.asset-data.peminjaman.detail', request()->peminjaman_asset_id) }}");
                $('#notifikasiId').data('link_perpanjangan', "{{ route('user.asset-data.peminjaman.perpanjangan.store', request()->peminjaman_asset_id) }}");
                showDetailPeminjaman($('#notifikasiId'));
            @endif
        })
    </script>
    <script>
        const getAllDataPeminjaman = (idContainer, status) => {
            $.ajax({
                url: '{{ route("user.asset-data.peminjaman.get-all-data") }}',
                data: {
                    guid_peminjam_asset: "{{ $user->guid ?? $user->id }}",
                    with: ['request_peminjaman_asset.kategori_asset'],
                    statusArray: status
                },
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function (index, value) {
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

        const generateTemplateApproval = (data) => {
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
                                <span class="text-grey text-end">${data.tanggal_peminjaman}</span>
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
                beforeSend: function () {
                    $(".loadingSpiner").show();
                },
                success: function (response) {
                    if (response.success) {
                        const data = response.data;
                        const array_asset = data.detail_peminjaman_asset.map((item) => (
                            JSON.parse(item.json_asset_data)
                        ));
                        $('#tanggalPeminjaman').val(data.tanggal_peminjaman);
                        $('.tanggalPengembalian').val(data.tanggal_pengembalian);
                        $('#statusPeminjaman').html(generateStatusPeminjaman(data.status));
                        $('.containerDetailPeminjaman').empty();
                        $(data.request_peminjaman_asset).each(function (index, value) {
                            const array_asset_item = array_asset.filter((item) => (
                                item.id_kategori_asset === value.id_kategori_asset
                            ));
                            $('.containerDetailPeminjaman').append(generateDetailPeminjaman(value.kategori_asset.nama_kategori, array_asset_item));
                        })
                        $('#alasanPeminjaman').text(data.alasan_peminjaman);

                        $('.containerPerpanjangan').empty();

                        if (data.perpanjangan_peminjaman_asset.length > 0) {
                            $(data.perpanjangan_peminjaman_asset).each(function (index, value) {
                                $('.containerPerpanjangan').append(generateListHistoryPerpanjangan(value));
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
                        } else if(data.status == 'selesai') {
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
            $(data_asset).each(function (index, value) {
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

        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);

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

        const submitForm = () => {
            $('.form-submit').submit();
        }
    </script>
@endsection
@section('content')
<input type="hidden" name="" id="notifikasiId">
<ul class="nav nav-tabs lined" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#overview2" role="tab" aria-selected="true">
            Menunggu
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#cards2" role="tab" aria-selected="false">
            Status
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
@include('pages.user.asset.peminjaman._modal_detail')
@endsection
