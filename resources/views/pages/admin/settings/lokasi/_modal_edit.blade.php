<div class="modal fade modalEditLokasi" id="modalCreate" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Induk Lokasi</label>
                        <select name="id_parent_lokasi" id="" class="form-control select2Lokasi">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kode Lokasi</label>
                        <input type="text" class="form-control" name="kode_lokasi">
                    </div>
                    <div class="form-group">
                        <label for="">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan" id="" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
