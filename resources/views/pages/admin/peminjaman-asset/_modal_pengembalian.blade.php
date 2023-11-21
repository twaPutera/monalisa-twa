<div class="modal fade modalPengembalian" id="modalPengembalian" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Rating Pengembalian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="{{ route('admin.peminjaman.detail-asset.change-status', $peminjaman->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="selesai">
                <div class="modal-body ">
                    <div class="d-flex justify-content-center">
                        <div class="rating">
                            <label>
                                <input type="radio" name="rating" value="1" />
                                <span class="icon">★</span>
                              </label>
                              <label>
                                <input type="radio" name="rating" value="2" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                              </label>
                              <label>
                                <input type="radio" name="rating" value="3" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                              </label>
                              <label>
                                <input type="radio" name="rating" value="4" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                              </label>
                              <label>
                                <input type="radio" name="rating" checked value="5" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                              </label>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;" id="keteranganPengembalianContainer">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan_pengembalian" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Selesaikan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
