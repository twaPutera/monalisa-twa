@extends('layouts.user.master')
@section('page-title', 'Approval')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataApproval('approvalContainer', 'pending');
            getAllDataApproval('historyApprovalContainer', 'disetujui');
        })
    </script>
    <script>
        const getAllDataApproval = (idContainer, status) => {
            $.ajax({
                url: '{{ route("user.approval.get-all-data") }}',
                data: {
                    guid_penerima_asset: "{{ $user->id }}",
                    with: ['detail_pemindahan_asset'],
                    status: status
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

        const generateTemplateApproval = (item) => {
            return `
                <div class="section mt-2">
                    <div class="card">
                        <div class="card-header">
                            Approval Pemindahan Aset
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${item.no_surat}</h5>
                            <p class="card-text">Pemindahan aset dengan kode ${item.asset.kode_asset} dari ${item.penyerah.nama}</p>
                            <a href="${item.link_detail}" class="btn btn-primary">Cek Detail</a>
                        </div>
                    </div>
                </div>
            `;
        }
    </script>
@endsection
@section('content')
<ul class="nav nav-tabs lined" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#overview2" role="tab" aria-selected="true">
            Approval
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#cards2" role="tab" aria-selected="false">
            History Approval
        </a>
    </li>
</ul>
<div class="tab-content mt-2">
    <div class="tab-pane fade active show" id="overview2" role="tabpanel">
        <div class="" id="approvalContainer"></div>
    </div>
    <div class="tab-pane fade" id="cards2" role="tabpanel">
        <div class="" id="historyApprovalContainer"></div>
    </div>
</div>
@endsection
