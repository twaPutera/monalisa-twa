<div class="modal fade" id="modalDetailPeminjaman" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Penghapusan Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-3 col-12">
                        <div class="mb-2">
                            <h5>Deskripsi Penghapusan Asset</h5>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Penghapusan</label>
                            <input type="date" class="form-control" id="tanggalPemutihan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">No. Berita Acara</label>
                            <input type="text" class="form-control" id="beritaAcara" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan Umum</label>
                            <textarea name="" class="form-control" readonly id="keteranganUmum" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">File Berita Acara</label>
                            <a href="" download id="fileBeritaAcara"
                                class="btn btn-primary shadow-custom btn-sm"><i class="fa fa-download"></i>
                                Unduh</a>
                        </div>
                        <div class="form-group">
                            <label for="">Dibuat Oleh</label>
                            <input type="text" class="form-control" id="createdBy" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-2">
                            <h5>Detail Item Dalam Penghapusan Asset</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th width="50px">#</th>
                                        <th>Kode Asset</th>
                                        <th>Nama Asset</th>
                                        <th>Lokasi Asset</th>
                                        <th>Keterangan Penghapusan Asset</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyDetailPeminjaman">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-2">
                            <h5>Status Approval Penghapusan Asset</h5>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary isDisabled">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
