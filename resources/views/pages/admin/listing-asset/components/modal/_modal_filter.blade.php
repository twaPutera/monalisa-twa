<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-12">
                    <label for="">Jenis Asset</label>
                    <select name="id_kategori_asset" class="form-control" id="kategoriAssetFilter">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Satuan</label>
                    <select name="id_satuan_asset" class="form-control" id="satuanAssetFilter">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Vendor</label>
                    <select name="id_vendor" class="form-control" id="vendorAssetFilter">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Jenis</label>
                    <select name="is_sparepart" class="form-control" id="isSparepartFilter">
                        <option value="">Semua Jenis</option>
                        <option value="0">Asset</option>
                        <option value="1">Sparepart</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Penghapusan Asset</label>
                    <select name="is_pemutihan" class="form-control" id="isPemutihanFilter">
                        <option value="all">Semua Asset</option>
                        <option value="0" selected>Asset Yang Tidak Dalam Penghapusan</option>
                        <option value="1">Asset Yang Dalam Penghapusan</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Status Asset</label>
                    <select name="is_status" class="form-control" id="isStatusFilter">
                        <option value="">Semua Status</option>
                        <option value="bagus">Bagus</option>
                        <option value="rusak">Rusak</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="tidak-lengkap">Tidak Lengkap</option>
                        <option value="pengembangan">Pengembangan</option>
                        <option value="tidak-ditemukan">Tidak Ditemukan</option>
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
