<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Peminjaman Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Awal</label>
                    <input type="text" name="tanggal_awal" id="tanggal_awal" readonly
                        class="form-control datepickerAwal mx-2" placeholder="Tanggal Awal">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Akhir</label>
                    <input type="text" name="tanggal_akhir" id="tanggal_akhir" readonly
                        class="form-control datepickerAkhir mr-2" placeholder="Tanggal Akhir">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Status Peminjaman</label>
                    <select name="status_peminjaman" class="form-control" id="status_peminjaman">
                        <option value="all" selected>Semua Status</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="pending">Pending</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="duedate">Due Date</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Status Approval</label>
                    <select name="status_approval" class="form-control" id="status_approval">
                        <option value="all" selected>Semua Status</option>
                        <option value="1">Disetujui</option>
                        <option value="0">Ditolak</option>
                        <option value="other">Pending</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Peminjam</label>
                    <select name="peminjam" class="form-control" id="peminjamSelect2">

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
