<div class="modal fade modalEditKeluhanData" id="modalEditKeluhanData" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Ubah Status Pengaduan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="#" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Tanggal Pengaduan Masuk</label>
                            <input type="text" class="form-control" disabled name="tanggal_pengaduan">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Asset</label>
                            <input type="text" class="form-control" disabled name="nama_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Lokasi Pengaduan</label>
                            <input type="text" class="form-control" disabled name="lokasi_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Prioritas Pengaduan</label>
                            <input type="text" class="form-control" disabled name="prioritas_pengaduan">
                        </div>
                        <div class="form-group">
                            <label for="">Dilaporkan Oleh</label>
                            <input type="text" class="form-control" disabled name="diajukan_oleh">
                        </div>

                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Kelompok Asset</label>
                            <input type="text" class="form-control" disabled name="kelompok_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Asset</label>
                            <input type="text" class="form-control" disabled name="jenis_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Catatan Pengaduan</label>
                            <textarea cols="30" rows="10" class="form-control" name="catatan_pengaduan" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Saat Ini</label>
                                <div id="status_laporan">

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Pengaduan</label>
                                <div>
                                    <select name="status_pengaduan" id="status_pengaduan" class="form-control"
                                        style="width: 200px" id="">
                                        <option value="diproses">Diproses</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan Admin</label>
                            <textarea cols="30" rows="10" class="form-control" name="catatan_admin"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Gambar/File Pendukung</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="preview-file-text">No File Choosen</span> <br>
                                    <span id="preview-file-error" class="text-danger"></span>
                                </div>
                                <label for="file_pendukung" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="file_pendukung" accept=".jpeg,.png,.jpg,.gif,.svg"
                                        class="d-none" name="file_pendukung">
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
