<div class="modal fade modalCreateAssetService" id="modalCreateAssetService" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.service-asset.store', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Pilih Lokasi</label>
                            <select name="id_lokasi" class="form-control" id="lokasiAssetCreateService">
                                @if (isset($asset->lokasi))
                                    <option selected="selected" value="{{ $asset->lokasi->id }}">
                                        {{ $asset->lokasi->nama_lokasi }}</option>
                                @else
                                    <option selected="selected" value="">Universitas Pertamina</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Asset</label>
                            <select class="form-control">
                                <option selected="selected">
                                    {{ $asset->deskripsi . '(' . $asset->kode_asset . ')' }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Opsi Tanggal Service</label>
                            <select name="select_service_date" class="form-control"
                                onchange="selectServiceDate(this.value)" id="">
                                <option value="">Pilih Tanggal Service</option>
                                <option value="baru">Tanggal Service Baru</option>
                                <option value="perencanaan">Berdasarkan Perencanaan Service</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="tanggalBaru">
                            <label for="">Tanggal Service</label>
                            <input type="text" value="{{ date('Y-m-d') }}" class="form-control datepickerCreate"
                                readonly name="tanggal_mulai_service">
                        </div>
                        <div class="form-group d-none" id="tanggalPerencanaan">
                            <label for="">Tanggal Service</label>
                            <select name="tanggal_mulai_perencanaan" class="form-control" id="listAssetServicesDate">

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Selesai</label>
                            <input type="text" class="form-control datepickerCreateSelesai" readonly
                                name="tanggal_selesai_service">
                        </div>
                        <div class="form-group">
                            <label for="">Permasalahan</label>
                            <textarea cols="30" rows="10" class="form-control" name="permasalahan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Kategori Service</label>
                            <select name="id_kategori_service" class="form-control" id="kategoriServiceCreate">

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tindakan</label>
                            <textarea cols="30" rows="10" class="form-control" name="tindakan"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <textarea cols="30" rows="10" class="form-control" name="catatan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Service</label>
                                <div>
                                    <select name="status_service" class="form-control" style="width: 200px"
                                        id="">
                                        <option value="onprogress" selected>Proses</option>
                                        <option value="backlog">Tertunda</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Kondisi Asset</label>
                                <div>
                                    <select name="status_kondisi" class="form-control" style="width: 200px"
                                        id="">
                                        <option value="baik" selected>Baik</option>
                                        <option value="rusak">Rusak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan Service</label>
                            <textarea cols="30" rows="10" class="form-control" name="keterangan_service"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">File/Gambar Hasil Service</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="preview-file-image-text">No File Choosen</span> <br>
                                    <span id="preview-file-image-error" class="text-danger"></span>
                                </div>
                                <label for="file_asset_service" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="file_asset_service" accept=".jpeg,.png,.jpg,.gif,.svg"
                                        class="d-none" name="file_asset_service">
                                </label>
                            </div>
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
