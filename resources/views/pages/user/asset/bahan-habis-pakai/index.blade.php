@extends('layouts.user.master')
@section('page-title', 'Daftar Permintaan Bahan Habis Pakai')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataRequest('pendingContainer', ['pending']);
            getAllDataRequest('dipinjamContainer', ['diproses', 'ditolak']);
            getAllDataRequest('selesaiContainer', ['selesai']);
        })
    </script>
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);
                $('#modalDetailBahanHabisPakai').modal('hide');
                setTimeout(() => {
                    window.location.href = '{{ route('user.asset-data.bahan-habis-pakai.index') }}';
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
        const getAllDataLogBahanHabisPakai = (idContainer, idRequest) => {
            $.ajax({
                url: '{{ route('user.asset-data.bahan-habis-pakai.get-all-data-log') }}',
                data: {
                    with: ['request_inventori'],
                    id_request: idRequest
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
        const generateStatusRequest = (status) => {
            let template = '';
            if (status == 'pending') {
                template = '<span class="badge badge-warning">Diajukan</span>';
            } else if (status == 'diproses') {
                template = '<span class="badge badge-primary">Diproses</span>';
            } else if (status == 'ditolak') {
                template = '<span class="badge badge-danger">Ditolak</span>';
            } else if (status == 'selesai') {
                template = '<span class="badge badge-success">Selesai</span>';
            }

            return template;

        }

        const getAllDataRequest = (idContainer, status) => {
            $.ajax({
                url: '{{ route('user.asset-data.bahan-habis-pakai.get-all-data') }}',
                data: {
                    created_by: "{{ $user->guid ?? $user->id }}",
                    with: ['detail_request_inventori', 'detail_request_inventori.inventori', ],
                    statusArray: status,
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
                        ${generateStatusRequest(data.status)}
                </div>
                    <p class="mb-0">${data.message}</p>
            </div>
            `
        }



        const generateTemplateApproval = (data) => {
            return `
            <a href="#" data-link_detail="${data.link_detail}"  onclick="showDetailRequest(this)" class="mb-2 bg-white px-2 py-2 d-block border-radius-sm border border-primary">
                <p class="text-dark mb-0 asset-deskripsi">${data.kode_request}</p>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center" style="width: 60%;">
                        <div class="" style="">
                            <p class="text-primary mb-0 asset-deskripsi" style="text-transform:capitalize"><i>${data.status}</i></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                        <div class="me-1 text-end">
                            <span class="text-grey text-end">${data.tanggal_permintaan}</span>
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
            $('#namaPengaju').empty();
            $('#unitKerja').empty();
            $('#jabatan').empty();
            $('#statusPermintaan').empty();
            $('#noMemo').empty();
            $('#alasanPermintaan').empty();
            $('.containerDetailPeminjaman').empty();
        }

        const showDetailRequest = (element) => {
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
                        console.log(data);
                        getAllDataLogBahanHabisPakai("logContainer", data.id);
                        $('#namaPengaju').append(data.created_by);
                        $('#unitKerja').append(data.unit_kerja);
                        $('#jabatan').append(data.jabatan);
                        $('#noMemo').append(data.no_memo);
                        $('#tanggalPengambilan').val(data.tanggal_pengambilan);
                        if (data.status == 'pending') {
                            template = '<span class="badge badge-warning">Diajukan</span>';
                        } else if (data.status == 'diproses') {
                            template = '<span class="badge badge-primary">Diproses</span>';
                        } else if (data.status == 'ditolak') {
                            template = '<span class="badge badge-danger">Ditolak</span>';
                        } else if (data.status == 'selesai') {
                            template = '<span class="badge badge-success">Selesai</span>';
                        }
                        $('#statusPermintaan').append(template);
                        $('#alasanPermintaan').append(data.alasan);

                        if (data.status == 'ditolak') {
                            $('#editOrDeleteButton').removeClass('d-none')
                            var url_edit =
                                "{{ route('user.asset-data.bahan-habis-pakai.edit', '') }}/" +
                                data.id;
                            $('#editPermintaanButton').attr('href', url_edit)
                        } else {
                            $('#editOrDeleteButton').addClass('d-none')
                        }

                        $(data.detail_request_inventori).each(function(index, value) {
                            $('.containerDetailPeminjaman').append(generateDetailPeminjaman(value
                                .inventori.kategori_inventori.nama_kategori,
                                value));
                        })
                        $(".loadingSpiner").hide();
                        $('#modalDetailBahanHabisPakai').modal('show');
                    }
                }
            })
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
                element +=
                    `<li>${value.inventori.nama_inventori} (${value.inventori.kode_inventori}) -- (Permintaan: ${value.qty}, Realisasi: ${value.realisasi != null ? value.realisasi : "-"})</li>`;
            })
            return element;
        }
    </script>
@endsection
@section('content')
    <ul class="nav nav-tabs lined" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#overview2" role="tab" aria-selected="true">
                Diajukan
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
    @include('pages.user.asset.bahan-habis-pakai._modal_detail')
@endsection
