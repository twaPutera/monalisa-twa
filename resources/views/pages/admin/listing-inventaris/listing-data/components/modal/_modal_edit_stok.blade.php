<div class="modal fade modalEditStokData" id="modalEditStokData" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Pengurangan Stok Bahan Habis Pakai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="#" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-12 col-md-4">
                        <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
                            <table id="tablePropertiEditStok" class="table table-striped">
                                <tr>
                                    <td width="40%">Jenis Bahan Habis Pakai</td>
                                    <td><strong class="kode_inventori"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Kategori Bahan Habis Pakai</td>
                                    <td><strong class="nama_kategori"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Merk Bahan Habis Pakai</td>
                                    <td><strong class="merk_inventaris"></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Jumlah Bahan Habis Pakai Sebelumnya</td>
                                    <td><strong class="jumlah_sebelumnya"></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Jumlah Bahan Habis Pakai Saat Ini</td>
                                    <td><strong class="jumlah_saat_ini"></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Deskripsi Bahan Habis Pakai</td>
                                    <td><strong class="deskripsi_inventaris"></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="">No Memorandum</label>
                            <select name="id_surat_memo_andin" class="form-control" id="memorandumAndin">

                            </select>
                            <input type="hidden" id="noMemoSurat" name="no_memo" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Penggunaan</label>
                            <input type="text" class="form-control datepickerCreate" readonly name="tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Bahan Habis Pakai Keluar</label>
                            <input type="number" min="0" class="form-control" name="jumlah_keluar">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
