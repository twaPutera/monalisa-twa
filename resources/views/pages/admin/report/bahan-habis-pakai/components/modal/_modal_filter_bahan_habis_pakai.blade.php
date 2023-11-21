<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Bahan Habis Pakai
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                {{-- <div class="form-group col-md-6 col-6">
                    <label for="">Kategori Bahan Habis Pakai</label>
                    <select name="" class="form-control" id="listKategoriAssetLocation">

                    </select>
                </div> --}}
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Mulai Permintaan</label>
                    <input type="text" name="tanggal_awal" readonly
                        class="form-control datepickerAwalPermintaan mx-2" placeholder="Tanggal Awal Permintaan">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Selesai Permintaan</label>
                    <input type="text" name="tanggal_akhir" readonly
                        class="form-control datepickerAkhirPermintaan mr-2" placeholder="Tanggal Akhir Permintaan">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Mulai Pengambilan</label>
                    <input type="text" name="tanggal_awal" readonly
                        class="form-control datepickerAwalPengambilan mx-2" placeholder="Tanggal Awal Pengambilan">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Selesai Pengambilan</label>
                    <input type="text" name="tanggal_akhir" readonly
                        class="form-control datepickerAkhirPengambilan mr-2" placeholder="Tanggal Akhir Pengambilan">
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="">Status Permintaan</label>
                    <select name="" class="form-control" id="statusPermintaan">
                        <option value="all" selected>Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" onclick="filterTableService()" data-dismiss="modal"
                    class="btn btn-primary">Filter</button>
            </div>
        </div>
    </div>
</div>
