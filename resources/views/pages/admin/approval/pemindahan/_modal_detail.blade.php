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
                <div class="modal-body row">
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Detail Pemindahan</h5>
                        </div>
                        <div class="form-group">
                            <label for="nama">No BAST</label>
                            <input type="text" class="form-control" id="noBast" readonly name="nama" placeholder="Nama" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Pemindahan</label>
                            <input type="text" class="form-control" id="tanggalPemindahan" readonly name="kode_satuan">
                        </div>
                        <div class="mt-2">
                            <h5>Detail Aset yang dipindahkan</h5>
                        </div>
                        <table id="tableProperti" class="table table-striped">
                            <tr>
                                <td width="40%">Deskripsi</td>
                                <td><strong id="deskripsiAsset"></strong></td>
                            </tr>
                            <tr>
                                <td width="40%">Asset Group</td>
                                <td><strong id="groupAsset"></strong></td>
                            </tr>
                            <tr>
                                <td width="40%">Kategori</td>
                                <td><strong id="kategoriAsset"></strong></td>
                            </tr>
                            <tr>
                                <td width="40%">Nilai perolehan</td>
                                <td><strong id="nilaiPerolehanAsset"></strong></td>
                            </tr>
                            <tr>
                                <td width="40%">No. Seri</td>
                                <td><strong id="noSeriAsset"></strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Penanggung Jawab Pemindahan</h5>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Penanggung Jawab Sebelumnya</label>
                            <input type="text" class="form-control" id="namaPenyerah" readonly name="nama" placeholder="Nama" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Jabatan Penanggung Jawab Sebelumnya</label>
                            <input type="text" class="form-control" id="jabatanPenyerah" readonly name="kode_satuan">
                        </div>
                        <div class="form-group">
                            <label for="">Unit Kerja Penanggung Jawab Sebelumnya</label>
                            <input type="text" class="form-control" id="unitPenyerah" readonly name="kode_satuan">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Penanggung Jawab Selanjutnya</label>
                            <input type="text" class="form-control" id="namaPenerima" readonly name="nama" placeholder="Nama" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Jabatan Penanggung Jawab Selanjutnya</label>
                            <input type="text" class="form-control" id="jabatanPenerima" readonly name="kode_satuan">
                        </div>
                        <div class="form-group">
                            <label for="">Unit Kerja Penanggung Jawab Selanjutnya</label>
                            <input type="text" class="form-control" id="unitPenerima" readonly name="kode_satuan">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary isDisabled">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
