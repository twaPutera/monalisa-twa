<div class="modal fade modalCreateOpname" id="modalCreateOpname" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Opname</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.opname-asset.store', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-6 col-6">
                        <div class="form-group">
                            <label for="">Tanggal Opname</label>
                            <input type="text" class="form-control dateTanggalOpaname" value="{{ date('Y-m-d') }}"
                                readonly name="tanggal_opname">
                        </div>
                        <div class="form-group">
                            <label for="">Lokasi Asset</label>
                            <select name="id_lokasi" class="form-control" id="lokasiAssetOpname">

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Opsi Perencanaan Service</label>
                            <select name="status_perencanaan" onchange="selectServicePerencanaan(this.value)"
                                class="form-control" id="">
                                <option value="nonaktif">Tidak Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                        </div>
                        <div id="perencanaanService" class="d-none">
                            <div class="form-group">
                                <label for="">Tanggal Perencanaan Service</label>
                                <input type="text" class="form-control dateTanggalPerencanaan"
                                    value="{{ date('Y-m-d') }}" readonly name="tanggal_services">
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan Perencanaan Services</label>
                                <textarea cols="30" rows="10" class="form-control" name="keterangan_services"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Kondisi Terakhir</label>
                                <div>
                                    @if ($asset->status_kondisi == 'bagus')
                                        <div class="badge badge-success">Bagus</div>
                                    @elseif($asset->status_kondisi == 'rusak')
                                        <div class="badge badge-danger">Rusak</div>
                                    @elseif($asset->status_kondisi == 'maintenance')
                                        <div class="badge badge-warning">Maintenance</div>
                                    @elseif($asset->status_kondisi == 'pengembangan')
                                        <div class="badge badge-info">Pengembangan</div>
                                    @elseif($asset->status_kondisi == 'tidak-ditemukan')
                                        <div class="badge badge-dark">Tidak Ditemukan</div>
                                    @else
                                        <div class="badge badge-dark">Tidak Lengkap</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Kondisi Asset</label>
                                <select name="status_kondisi"
                                    onchange="changeStatusKondisiAsset(this.value,'status_akunting')"
                                    class="form-control" style="width: 200px" id="status_kondisi">
                                    <option value="bagus">Bagus</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="tidak-lengkap">Tidak Lengkap</option>
                                    <option value="pengembangan">Pengembangan</option>
                                    <option value="tidak-ditemukan">Tidak Ditemukan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Tingkat Kritikal</label>
                                <div>
                                    <select name="kritikal" class="form-control" style="width: 200px" id="">
                                        <option value="10">High</option>
                                        <option value="5">Medium</option>
                                        <option value="1">Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Akunting Aset</label>
                                <select name="status_akunting" class="form-control" id="status_akunting">
                                    @foreach ($list_status as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <textarea cols="30" rows="10" class="form-control" name="catatan"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Gambar Asset Terbaru</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="preview-file-image-terbaru-text">No File Choosen</span> <br>
                                    <span id="preview-file-image-terbaru-error" class="text-danger"></span>
                                </div>
                                <label for="file_asset_terbaru" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="file_asset_terbaru" accept=".jpeg,.png,.jpg,.gif,.svg"
                                        class="d-none" name="gambar_asset">
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
