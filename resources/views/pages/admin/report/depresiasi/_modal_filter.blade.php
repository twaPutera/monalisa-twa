<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Laporan Depresiasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-12">
                    <label for="">Bulan Depresiasi</label>
                    <input type="text" class="form-control monthpicker" readonly placeholder="Pilih Bulan">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tahun</label>
                    <input type="text" class="form-control yearpicker" id="kt_datepicker_2" readonly placeholder="Pilih Tahun" />
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="">Kelompok Aset</label>
                    <select name="id_group_asset" class="form-control" id="groupAssetCreate">

                    </select>
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="">Jenis Asset</label>
                    <select name="id_kategori_asset" class="form-control" id="kategoriAssetCreate">

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
