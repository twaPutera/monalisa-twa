<div class="modal fade" id="modalDetailPeminjaman" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="" method="POST">
                @csrf
                <div class="modal-body ">
                    <div class="mb-2">
                        <h5>Status Approval Peminjaman</h5>
                    </div>
                    <div class="form-group">
                        <label for="nama">Tanggal Approval</label>
                        <input type="text" class="form-control" id="tanggalApproval" disabled value="">
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control custom-select isDisabled" id="statusApproval">
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan" class="form-control isDisabled" id="keteranganApproval" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary isDisabled">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
