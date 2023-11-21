<div class="col-md-4 col-12">
    <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
        <table id="tableProperti" class="table table-striped">
            <tr>
                <td width="40%">Tanggal Mulai</td>
                <td><strong>{{ $listing_asset_service->tanggal_mulai ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Kode Services</td>
                <td><strong>{{ $listing_asset_service->kode_services ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Tanggal Selesai</td>
                <td><strong>{{ $listing_asset_service->tanggal_selesai ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Kode Asset</td>
                <td><strong>{{ $listing_asset_service->detail_service->asset_data->kode_asset ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Deskripsi Asset</td>
                <td><strong>{{ $listing_asset_service->detail_service->asset_data->deskripsi ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Jenis Asset</td>
                <td><strong>{{ $listing_asset_service->detail_service->asset_data->kategori_asset->nama_kategori ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Tipe</td>
                <td><strong>{{ ucWords($listing_asset_service->detail_service->asset_data->is_inventaris) == 1 ? 'Inventaris' : 'Asset' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Status Kondisi Asset</td>
                <td><strong>{{ ucWords($listing_asset_service->status_kondisi) }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Kelompok Asset</td>
                <td><strong>{{ $listing_asset_service->detail_service->asset_data->kategori_asset->group_kategori_asset->nama_group ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Permasalahan</td>
                <td><strong>{{ $listing_asset_service->detail_service->permasalahan ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Tindakan</td>
                <td><strong>{{ $listing_asset_service->detail_service->tindakan ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Catatan</td>
                <td><strong>{{ $listing_asset_service->detail_service->catatan ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Keterangan Service</td>
                <td><strong>{{ $listing_asset_service->keterangan ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Status Service</td>
                <td><strong>{{ ucWords($listing_asset_service->status_service) }}</strong></td>
            </tr>
        </table>
    </div>
</div>
<div class="col-12 col-md-8">
    <label for="">Data Riwayat Service</label>
    <div class="table-responsive">
        <table class="table table-striped mb-0" id="datatableLogService2">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Log</th>
                    <th>Status</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</div>

@include('pages.admin.services.components.js._data_modal_detail_js')
