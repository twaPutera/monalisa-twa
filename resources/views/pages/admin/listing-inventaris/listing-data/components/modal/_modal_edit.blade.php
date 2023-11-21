<div class="modal fade modalEditInventarisData" id="modalEditInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Bahan Habis Pakai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="#" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="">Kategori Bahan Habis Pakai</label>
                            <div class="input-group mb-2">
                                <select name="id_kategori_inventori" class="form-control selectKategoriData"
                                    id="selectKategoriDataEdit">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Bahan Habis Pakai</label>
                            <input type="text" class="form-control" name="kode_inventori">
                        </div>
                        <div class="form-group">
                            <label for="">Satuan Bahan Habis Pakai</label>
                            <div class="input-group mb-2">
                                <select name="id_satuan_inventori" class="form-control selectSatuanData"
                                    id="selectSatuanDataEdit">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Merk Bahan Habis Pakai</label>
                            <input type="text" class="form-control" name="nama_inventori">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Sebelumnya</label>
                            <input type="number" min="0" class="form-control" disabled name="stok_sebelumnya">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Saat Ini</label>
                            <input type="number" min="0" class="form-control" disabled name="stok_saat_ini">
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="">Deskripsi Bahan Habis Pakai</label>
                            <textarea cols="30" rows="20" class="form-control" name="deskripsi_inventori"></textarea>
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
