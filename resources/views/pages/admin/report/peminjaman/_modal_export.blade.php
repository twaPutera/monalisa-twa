<div class="modal fade modalExportPeminjamanAsset" id="modalExportPeminjamanAsset" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Export History Peminjaman Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Awal</label>
                    <input type="text" name="start_date" id="exportStartDate" readonly class="form-control datepickerAwal mx-2" placeholder="Tanggal Awal">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Akhir</label>
                    <input type="text" name="end_date" id="exportEndDate" readonly class="form-control datepickerAkhir mr-2" placeholder="Tanggal Akhir">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" onclick="exportPeminjaman()" data-dismiss="modal" class="btn btn-primary">Export</button>
            </div>
        </div>
    </div>
</div>
