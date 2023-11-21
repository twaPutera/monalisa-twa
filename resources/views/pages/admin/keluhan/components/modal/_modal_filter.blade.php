<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Pengaduan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-12">
                    <label for="">Lokasi</label>
                    <select name="id_lokasi" class="form-control lokasiSelect" id="lokasiAssetCreateService">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Asset</label>
                    <select name="id_asset" class="form-control" id="listAssetLocation">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Status Pengaduan</label>
                    <select name="status_pengaduan" class="form-control" id="statusPengaduanFilter">
                        <option value="all" selected>Semua Status</option>
                        <option value="dilaporkan">Laporan Masuk</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Prioritas Pengaduan</label>
                    <select name="prioritas_pengaduan" class="form-control" id="prioritasPengaduanFilter">
                        <option value="all" selected>Semua Prioritas</option>
                        <option value="10">High</option>
                        <option value="5">Medium</option>
                        <option value="1">Low</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" onclick="filterTableAsset()" data-dismiss="modal"
                    class="btn btn-primary">Filter</button>
            </div>
        </div>
    </div>
</div>
