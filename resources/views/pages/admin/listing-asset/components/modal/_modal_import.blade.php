<div class="modal fade modalImportAsset" id="modalImport" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Import Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" id="formImportAsset"
                action="{{ route('admin.listing-asset.import-asset-data') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="">Unduh Format File Importt</label>
                            <a href="{{ route('admin.listing-asset.download-template-import') }}" target="_blank"
                                class="btn btn-sm btn-icon btn-success"><i class="fa fa-file"></i></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Import Data Asset (xlsx, xls, csv)</label>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span id="preview-file-excel-text">No File Choosen</span> <br>
                                <span id="preview-file-excel-error" class="text-danger"></span>
                            </div>
                            <label for="fileImport" class="btn btn-primary">
                                Upload
                                <input type="file" id="fileImport" name="file" accept=".csv,.xlsx,.xls"
                                    class="d-none">
                            </label>
                        </div>
                    </div>
                    <div class="form-group error-import-asset" style="display: none;">
                        <p class="text-danger"><strong>Error:</strong></p>
                        <ul class="error-import-container">

                        </ul>
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
