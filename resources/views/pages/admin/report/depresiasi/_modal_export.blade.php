<div class="modal fade modalExport" id="modalExport" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Export Data Depresiasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form action="{{ route('admin.report.depresiasi.download-export') }}" method="GET">
                <div class="modal-body row">
                    <div class="form-group col-md-12 col-12">
                        <label for="">Tahun</label>
                        <input type="text" class="form-control yearpicker" name="year" value="{{ date('Y') }}" id="yearExport" readonly placeholder="Pilih Tahun" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" onclick="exportDepresiasi()" data-dismiss="modal" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
