<div class="modal fade modalCreateImageAsset" id="modalCreateImageAsset" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Gambar Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.image-asset.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id_asset" name="id_asset" value="{{ $asset->id }}">
                    <div class="form-group">
                        <label for="">Gambar Asset</label>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span id="preview-file-text">No File Choosen</span> <br>
                                <span id="preview-file-error" class="text-danger"></span>
                            </div>
                            <label for="gambar_asset" class="btn btn-primary">
                                Upload
                                <input type="file" id="gambar_asset" accept=".jpeg,.png,.jpg,.gif,.svg"
                                    class="d-none" name="gambar_asset">
                            </label>
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
