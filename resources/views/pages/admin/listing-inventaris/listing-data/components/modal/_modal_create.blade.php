<div class="modal fade modalCreateInventarisData" id="modalCreateInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Bahan Habis Pakai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-inventaris.store') }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="">Jenis Penambahan</label>
                            <div class="input-group mb-2">
                                <select name="id_jenis_penambahan" class="form-control"
                                    onchange="jenisPenambahan(this.value)" id="">
                                    <option value="">Pilih Jenis Penambahan Bahan Habis Pakai</option>
                                    <option value="baru">Tambah Inventaris Baru</option>
                                    <option value="lama">Gunakan Yang Sudah Ada</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-none" id="form-inventaris">
                            <label for="">Daftar Bahan Habis Pakai</label>
                            <div class="input-group mb-2">
                                <select name="id_inventaris" class="form-control selectInventarisData" id="">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Kategori Bahan Habis Pakai</label>
                            <div class="input-group mb-2">
                                <select name="id_kategori_inventori" class="form-control selectKategoriData"
                                    id="selectKategoriDataCreate">

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
                                    id="selectSatuanDataCreate">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Merk Bahan Habis Pakai</label>
                            <input type="text" class="form-control" name="nama_inventori">
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal Masuk</label>
                            <input type="text" class="form-control datepickerCreate" readonly name="tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Masuk</label>
                            <input type="number" min="0" class="form-control" name="stok">
                        </div>
                        <div class="form-group">
                            <label for="">Harga Beli</label>
                            <input type="number" min="0" class="form-control" name="harga_beli">
                        </div>
                        <div class="form-group">
                            <label for="">Deskripsi Bahan Habis Pakai</label>
                            <textarea cols="30" rows="15" class="form-control" name="deskripsi_inventori"></textarea>
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
