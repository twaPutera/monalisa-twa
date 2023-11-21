@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
@endsection
@section('custom_css')
    <style>
        .rating {
            display: inline-block;
            position: relative;
            height: 50px;
            line-height: 50px;
            font-size: 50px;
        }

        .rating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            cursor: pointer;
        }

        .rating label:last-child {
            position: static;
        }

        .rating label:nth-child(1) {
            z-index: 5;
        }

        .rating label:nth-child(2) {
            z-index: 4;
        }

        .rating label:nth-child(3) {
            z-index: 3;
        }

        .rating label:nth-child(4) {
            z-index: 2;
        }

        .rating label:nth-child(5) {
            z-index: 1;
        }

        .rating label input {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        .rating label .icon {
            float: left;
            color: transparent;
        }

        .rating label:last-child .icon {
            color: #000;
        }

        .rating:not(:hover) label input:checked~.icon,
        .rating:hover label:hover input~.icon {
            color: #09f;
        }

        .rating label input:focus:not(:checked)~.icon:last-child {
            color: #000;
            text-shadow: 0 0 5px #09f;
        }
    </style>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        let arrayKategori = [];
        let isTableDetailDraw = false;
        var table = $('#tableDetailPeminjaman');
        var table2 = $('#addDetailPeminjaman');
        var table3 = $('#logPeminjaman');
        $(document).ready(function() {
            @foreach ($peminjaman->request_peminjaman_asset as $item)
                arrayKategori.push({
                    "id": "{{ $item->id_kategori_asset }}",
                    "nama_kategori": "{{ $item->kategori_asset->nama_kategori }}",
                    "jumlah": "{{ $item->jumlah }}",
                });
            @endforeach
            table.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                searching: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.peminjaman.detail-asset.datatable') }}",
                    data: function(d) {
                        d.id_peminjaman_asset = "{{ $peminjaman->id }}";
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
                        data: 'asset_data.deskripsi'
                    },
                    {
                        data: 'asset_data.id_kategori_asset',
                    },
                    {
                        data: 'asset_data.kode_asset'
                    },
                    {
                        data: 'asset_data.no_seri'
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [3],
                        render: function(data, type, full, meta) {
                            const kategori = arrayKategori.find(item => item.id == data);
                            return kategori.nama_kategori;
                        }
                    }
                ],
                rowCallback: function(row, data, index) {
                    // arrayKategori = arrayKategori.map((item) => {
                    //     return {
                    //         id: item.id,
                    //         nama_kategori: item.nama_kategori,
                    //         jumlah: item.id == data.asset_data.id_kategori_asset ? (parseInt(
                    //             item.jumlah) - 1) : item.jumlah,
                    //     };
                    // });
                }
            });

            table2.DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                orderings: false,
                searching: false,
                bLengthChange: false,
                ajax: {
                    url: "{{ route('admin.listing-asset.datatable') }}",
                    data: function(d) {
                        d.is_pemutihan = 0;
                        d.is_draft = 0;
                        d.global = true;
                        d.id_kategori_asset = $('#kategoriAssetFilter').val();
                        d.searchKeyword = $('#searchAsset').val();
                        d.list_peminjaman = true;
                        d.id_peminjaman = "{{ $peminjaman->id }}";
                        d.status_kondisi = 'bagus';
                        d.is_pinjam = '1';
                        d.is_draft = '0';
                    }
                },
                columns: [{
                        name: 'id',
                        data: 'id',
                        class: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        name: 'kode_asset',
                        data: 'kode_asset'
                    },
                    {
                        name: 'deskripsi',
                        data: 'deskripsi'
                    },
                    {
                        name: 'nama_kategori',
                        data: 'nama_kategori'
                    },
                    {
                        name: 'nama_lokasi',
                        data: 'nama_lokasi'
                    },
                    {
                        name: 'no_seri',
                        data: 'no_seri'
                    },
                    {
                        name: 'status_kondisi',
                        data: 'status_kondisi'
                    },

                ],
                columnDefs: [{
                        targets: [0],
                        render: function(data, type, full, meta) {
                            return `<input type="checkbox" class="check-item" data-id_kategori_asset="${full.id_kategori_asset}" onclick="checkIfQuotaExists(this)" onchange="checklistAsset(this)" name="id_asset[]" value="${data}">`;
                        },
                        orderable: false,
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            let element = '';
                            if (data == 'bagus') {
                                element =
                                    `<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Bagus</span>`;
                            } else if (data == 'rusak') {
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
                            }

                            return element;
                        }
                    }
                    //Custom template data
                ],
                "drawCallback": function(settings) {
                    console.log('drawCallback:');

                    setTimeout(function() {
                        rerenderCheckbox();
                    }, 100);
                },
            });

            table3.DataTable({
                responsive: true,
                // searchDelay: 500,
                processing: true,
                searching: false,
                // ordering: false,
                bLengthChange: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.report.history-peminjaman.datatable') }}",
                    data: function(d) {
                        d.searchKeyword = $('#searchDepresiasi').val();
                        d.peminjaman_asset_id = "{{ $peminjaman->id }}";
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
                        data: "created_at",
                        name: 'created_at'
                    },
                    {
                        name: 'created_by_name',
                        data: 'created_by_name',
                        orderable: false,
                    },
                    {
                        name: 'log_message',
                        data: 'log_message',
                        orderable: false,
                    }
                ],
                columnDefs: [
                    //Custom template data
                    {
                        targets: [1],
                        render: function(data, type, full, meta) {
                            return formatDateIntoIndonesia(data);
                        }
                    }
                ],
            });

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    console.log(data);
                    if (data.data.command == 'storeManyDetailPeminjaman' || data.data.command ==
                        'deleteDetailPeminjaman') {
                        // arrayKategori = data.data.quota;
                    }
                    if (data.data.command == 'changeStatus') {
                        window.location.reload();
                    }
                    $(formElement).trigger('reset');
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    let modal = $(formElement).closest('.modal');
                    modal.modal('hide');
                    table.DataTable().ajax.reload();
                    table2.DataTable().ajax.reload();
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
        });

        const checkIfQuotaExists = (element) => {
            const id_kategori_asset = $(element).data('id_kategori_asset');
            const kategori = arrayKategori.find(item => item.id == id_kategori_asset);
            // console.log(arrayKategori, kategori, $(element).is(':checked'));
            if (kategori.jumlah == 0 && $(element).is(':checked')) {
                showToastError('Gagal', 'Kuota untuk kategori ini sudah habis');
                $(element).prop('checked', false);
            } else {
                // console.log(id_kategori_asset);
                if ($(element).is(':checked')) {
                    arrayKategori = arrayKategori.map((item) => {
                        return {
                            id: item.id,
                            nama_kategori: item.nama_kategori,
                            jumlah: item.id == id_kategori_asset ? (parseInt(item.jumlah) - 1) : item.jumlah,
                        };
                    });
                } else {
                    arrayKategori = arrayKategori.map((item) => {
                        return {
                            id: item.id,
                            nama_kategori: item.nama_kategori,
                            jumlah: item.id == id_kategori_asset ? (parseInt(item.jumlah) + 1) : item.jumlah,
                        };
                    });
                }
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
            table2.DataTable().ajax.reload();
        }

        $('input[name="rating"]').on('change', function() {
            let rating = $(this).val();
            if (rating < 4) {
                $('#keteranganPengembalianContainer').show();
            } else {
                $('#keteranganPengembalianContainer').hide();
            }
        });

        $('#modalPengembalian').on('show.bs.modal', function(e) {
            const tanggalPengembalian = new Date($('#tanggalPengembalian').val());
            const today = new Date();
            const diffTime = Math.abs(today - tanggalPengembalian);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays == 1) {
                $('input[name="rating"][value="3"]').prop('checked', true);
                $('#keteranganPengembalianContainer').show();
            } else if (diffDays > 1) {
                $('input[name="rating"][value="1"]').prop('checked', true);
                $('#keteranganPengembalianContainer').show();
            } else {
                $('input[name="rating"][value="5"]').prop('checked', true);
                $('#keteranganPengembalianContainer').hide();
            }
        });
    </script>
@endsection
@section('main-content')
    <input type="hidden" id="jsonTempAsset" value="[]">
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Detail Peminjaman
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                @if ($peminjaman->status == 'disetujui')
                                    <form
                                        action="{{ route('admin.peminjaman.detail-asset.change-status', $peminjaman->id) }}"
                                        class="form-confirm d-inline" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="dipinjam">
                                        <button type="submit" class="btn btn-sm btn-primary"><i
                                                class="fas fa-check mr-2"></i> Proses</button>
                                    </form>
                                @elseif($peminjaman->status == 'ditolak')
                                    <button class="btn btn-sm btn-danger" style="pointer-events: none;"><i
                                            class="fas fa-times mr-2"></i> Ditolak</button>
                                @elseif($peminjaman->status == 'diproses')
                                    <button class="btn btn-sm btn-success" style="pointer-events: none;"><i
                                            class="fas fa-check mr-2"></i> Diproses</button>
                                @elseif($peminjaman->status == 'dipinjam')
                                    <button type="button" onclick="openModalByClass('modalPengembalian')"
                                        class="btn btn-sm btn-danger"><i class="fas fa-check mr-2"></i> Selesaikan</button>
                                @elseif($peminjaman->status == 'duedate')
                                    <button class="btn btn-sm btn-warning" style="pointer-events: none;"><i
                                            class="fas fa-calendar-times mr-2"></i> Terlambat</button>
                                    <button type="button" onclick="openModalByClass('modalPengembalian')"
                                        class="btn btn-sm btn-danger"><i class="fas fa-check mr-2"></i> Selesaikan</button>
                                @elseif($peminjaman->status == 'selesai')
                                    <button class="btn btn-sm btn-success" style="pointer-events: none;"><i
                                            class="fas fa-check mr-2"></i> Selesai</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="kt-portlet shadow-custom">
                                <div class="kt-portlet__head px-4">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Deskripsi Peminjaman
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="form-group">
                                        <label for="nama">Kode Peminjaman</label>
                                        <input type="text" class="form-control" id="codePeminjam" readonly name="nama"
                                            placeholder="Kode" value="{{ $peminjaman->code }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama Peminjam</label>
                                        <input type="text" class="form-control" id="namaPeminjam" readonly name="nama"
                                            placeholder="Nama" value="{{ $peminjam->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Peminjaman</label>
                                        <div class="d-flex">
                                            <input type="date" class="form-control w-75" id="tanggalPeminjam" readonly
                                                name="" value="{{ $peminjaman->tanggal_peminjaman }}">
                                            <input type="text" class="form-control w-25" id="jamMulai" readonly
                                                name="" value="{{ $peminjaman->jam_mulai ?? '-' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Pengembalian</label>
                                        <div class="d-flex">
                                            <input type="date" class="form-control w-75" id="tanggalPengembalian"
                                                readonly name="" value="{{ $peminjaman->tanggal_pengembalian }}">
                                            <input type="text" class="form-control w-25" id="jamAkhir" readonly
                                                name="" value="{{ $peminjaman->jam_selesai ?? '-' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Alasan Peminjaman</label>
                                        <textarea name="" class="form-control" readonly id="alasanPeminjaman" cols="30" rows="10">{{ $peminjaman->alasan_peminjaman }}</textarea>
                                    </div>
                                    @if ($peminjaman->status == 'selesai')
                                        <div>
                                            <label for="nama">Rating</label>
                                            <div>
                                                <div class="rating">
                                                    <label>
                                                        <input type="radio"
                                                            @if ($peminjaman->rating == 1) checked @endif disabled
                                                            name="ratingView" value="1" />
                                                        <span class="icon">★</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio"
                                                            @if ($peminjaman->rating == 2) checked @endif disabled
                                                            name="ratingView" value="2" />
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio"
                                                            @if ($peminjaman->rating == 3) checked @endif disabled
                                                            name="ratingView" value="3" />
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio"
                                                            @if ($peminjaman->rating == 4) checked @endif disabled
                                                            name="ratingView" value="4" />
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio"
                                                            @if ($peminjaman->rating == 5) checked @endif disabled
                                                            name="ratingView" value="5" />
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                        <span class="icon">★</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Keterangan Pengembalian</label>
                                            <textarea name="" class="form-control" readonly id="" cols="30" rows="10">{{ $peminjaman->keterangan_pengembalian }}</textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="kt-portlet shadow-custom">
                                        <div class="kt-portlet__head px-4">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">
                                                    Request Peminjaman
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="50px">No</th>
                                                            <th>Nama Kategori</th>
                                                            <th width="50px">Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableBodyDetailPeminjaman">
                                                        @foreach ($peminjaman->request_peminjaman_asset as $item)
                                                            <tr>
                                                                <td>
                                                                    {{ $loop->iteration }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->kategori_asset->nama_kategori }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->jumlah }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 col-12">
                                    <div class="kt-portlet shadow-custom">
                                        <div class="kt-portlet__head px-4">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">
                                                    Request Peminjaman
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="50px">No</th>
                                                            <th>Tanggal Expired</th>
                                                            <th>Tanggal Perpanjangan</th>
                                                            <th width="50px">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="">
                                                        @foreach ($peminjaman->perpanjangan_peminjaman_asset as $item)
                                                            <tr>
                                                                <td>
                                                                    {{ $loop->iteration }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->tanggal_expired_sebelumnya }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->tanggal_expired_perpanjangan }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->status }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet shadow-custom">
                                <div class="kt-portlet__head px-4">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Realisasi Peminjaman
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-wrapper">
                                            <div class="kt-portlet__head-actions">
                                                @if ($peminjaman->status == 'disetujui')
                                                    <button type="button"
                                                        onclick="openModalByClass('modalCreateDetailPeminjaman')"
                                                        class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah
                                                        Data </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="table-responsive">
                                        <table class="table dt_table table-striped table-bordered"
                                            id="tableDetailPeminjaman">
                                            <thead>
                                                <tr>
                                                    <th width="50px">No</th>
                                                    <th width="50px">#</th>
                                                    <th>Nama Aset</th>
                                                    <th>Nama Kategori</th>
                                                    <th>Kode Aset</th>
                                                    <th>No Seri</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet shadow-custom">
                                <div class="kt-portlet__head px-4">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Log Peminjaman
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-wrapper">
                                            <div class="kt-portlet__head-actions">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="table-responsive">
                                        <table class="table table-striped dt_table" id="logPeminjaman">
                                            <thead>
                                                <tr>
                                                    <th width="50px">No</th>
                                                    <th>Tanggal</th>
                                                    <th>Pembuat</th>
                                                    <th>Log</th>
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
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.peminjaman-asset._modal_create_detail_peminjaman')
    @include('pages.admin.peminjaman-asset._modal_pengembalian')
@endsection
