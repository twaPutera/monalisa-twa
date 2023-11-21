@extends('layouts.user.master-detail')
@section('page-title', 'Detail Pemindahan')
@section('custom-js')
    <script>
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
            $('#dialogApprove').modal('hide');
            if (!errors.success) {
                changeTextToast('toastDanger', errors.message);
                toastbox('toastDanger', 2000)
            }
        });
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.approval.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <div class="section mt-2">
        <h2 style="color: #6F6F6F;">{{ $asset_data->deskripsi }}</h2>

        <div class="mt-2">
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Group</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ $asset_data->kategori_asset->group_kategori_asset->nama_group ?? 'Tidak Ada Group' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Kategori</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ $asset_data->kategori_asset->nama_kategori ?? 'Tidak Ada Kategori' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Lokasi</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">{{ $asset_data->lokasi->nama_lokasi ?? 'Tidak Ada Lokasi' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Tanggal Register</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ App\Helpers\DateIndoHelpers::formatDateToIndo($asset_data->tgl_register) }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Status Terakhir</p>
                    </div>
                    <div class="col text-end">
                        <span class="badge badge-success px-3">Baik</span>
                        {{-- <p class="mb-0 text-green text-end">Baik</p> --}}
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Catatan</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">{{ $last_service->detail_service->catatan ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Log Service Terakhir</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ isset($last_service) ? App\Helpers\DateIndoHelpers::formatDateToIndo($last_service->tanggal_selesai) : '-' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Dicek Oleh</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            <strong>{{ isset($last_service) ? $last_service->user_guid : '-' }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Spesifikasi</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">{{ $asset_data->spesifikasi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (isset($approval))
        <div class="modal fade dialogbox" id="dialogApprove" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pemindahan Asset</h5>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menerima asset ini?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ $approval->linkApproval() }}" class="form-submit" method="POST">
                            @csrf
                            <input type="hidden" value="disetujui" name="status">
                            <div class="btn-inline">
                                <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal">Tutup</a>
                                <button type="submit" class="btn btn-text-primary">Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('button-menu')
    @if ($pemindahan_asset->status == 'pending')
        <button type="button" data-bs-toggle="modal" data-bs-target="#dialogApprove"
            class="btn btn-primary border-radius-sm px-5">Terima</button>
    @elseif($pemindahan_asset->status == 'disetujui')
        <a target="_blank" href="{{ route('user.listing-asset.pemindahan-asset.print-bast', $pemindahan_asset->id) }}"
            class="btn btn-success border-radius-sm px-5">Download BAST</a>
    @endif
@endsection
