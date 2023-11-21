<div class="col-md-4 col-12">
    <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
        <table id="tableProperti" class="table table-striped">
            <tr>
                <td width="40%">Tanggal Pengaduan Masuk</td>
                <td><strong>{{ $listing_keluhan->tanggal_pengaduan ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Nama Asset</td>
                <td><strong>{{ $listing_keluhan->asset_data->deskripsi ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Lokasi Pengaduan</td>
                <td><strong>{{ $listing_keluhan->lokasi->nama_lokasi ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Dilaporkan Oleh</td>
                <td><strong>{{ $listing_keluhan->created_by_name ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Kelompok Asset</td>
                <td><strong>{{ $listing_keluhan->asset_data->kategori_asset->group_kategori_asset->nama_group ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Jenis Asset</td>
                <td><strong>{{ $listing_keluhan->asset_data->kategori_asset->nama_kategori ?? '-' }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Catatan Pengaduan</td>
                <td><strong>{{ $listing_keluhan->catatan_pengaduan ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Catatan Admin</td>
                <td><strong>{{ $listing_keluhan->catatan_admin ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Status Pengaduan Saat Ini</td>
                <td><strong>{{ $listing_keluhan->status_pengaduan == 'dilaporkan' ? 'Laporan Masuk' : ucWords($listing_keluhan->status_pengaduan) }}</strong>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="col-12 col-md-8">
    <label for="">Data Riwayat Pengaduan</label>
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

@include('pages.admin.report.pengaduan.components.js._data_modal_detail_js')
