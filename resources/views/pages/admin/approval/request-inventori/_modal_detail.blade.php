<div class="modal fade" id="modalDetailRequestInventori" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Request Bahan Habis Pakai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-4 col-12">
                        <div class="deskripsiPeminjaman">
                            <h5>Deskripsi Pengaju</h5>
                            <div class="form-group">
                                <label for="nama">Nama Pengaju</label>
                                <input type="text" class="form-control" id="namaPengaju" readonly name="nama"
                                    placeholder="Nama" value="">
                            </div>
                            <div class="form-group">
                                <label for="nama">Unit Kerja</label>
                                <input type="text" class="form-control" id="unitKerjaPengaju" readonly name="nama"
                                    placeholder="Nama" value="">
                            </div>
                            <div class="form-group">
                                <label for="nama">Jabatan</label>
                                <input type="text" class="form-control" id="jabatanPengaju" readonly name="nama"
                                    placeholder="Nama" value="">
                            </div>
                            <div class="form-group">
                                <label for="nama">No Memo</label>
                                <input type="text" class="form-control" id="noMemo" readonly name="nama"
                                    placeholder="Nama" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Pengambilan</label>
                                <input type="date" class="form-control" id="tanggalPengambilan" readonly
                                    name="">
                            </div>
                            <div class="form-group">
                                <label for="">Alasan Pengajuan</label>
                                <textarea name="" class="form-control" readonly id="alasanPengajuan" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Detail barang yang diminta</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Nama Bahan Habis Pakai</th>
                                        <th width="50px">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyDetailRequestInventori">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Status Approval Permintaan</h5>
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
