<div class="modal fade modalCreateInventarisData" id="modalCreateInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Penghapusan Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.pemutihan-asset.store') }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Tanggal Penghapusan Asset</label>
                            <input type="text" class="form-control datepickerCreate" readonly name="tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">No Berita Acara</label>
                            <input type="text" class="form-control" name="no_berita_acara">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Penghapusan Asset</label>
                            <input type="text" class="form-control" name="nama_pemutihan">
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan Penghapusan Asset</label>
                            <textarea cols="30" rows="10" class="form-control" name="keterangan_pemutihan"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">File Berita Acara</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="preview-file-image-text">No File Choosen</span> <br>
                                    <span id="preview-file-image-error" class="text-danger"></span>
                                </div>
                                <label for="file_asset_service" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="file_asset_service" accept=".pdf,.docx,.doc"
                                        class="d-none" name="file_berita_acara">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="row">
                            <div class="form-group col-12 col-md-6">
                                <label for="">Jenis Asset</label>
                                <select name="" onchange="filterTableService()" id="groupAssetCreate"
                                    class="form-control jenispicker mr-2">

                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="">Status Kondisi</label>
                                <select name="" onchange="filterTableService()"
                                    class="form-control kondisipicker mr-2">
                                    <option value="semua">Semua</option>
                                    <option value="bagus">Bagus</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">List Asset Yang Akan Penghapusan Asset</label>
                            <div class="alert alert-danger d-none" id="alert-list-asset">List asset yang dalam penghapusan asset
                                wajib diisi</div>
                            <div class="table-responsive">
                                <div class="input-group mr-3" style="width: 250px;">
                                    <input type="text" id="searchAsset" onkeyup="filterTableService()"
                                        class="form-control form-control-sm" placeholder="Search for...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-icon" onclick="filterTableService()"
                                            id="searchButton" type="button"><i class="fa fa-search"></i></button>
                                        <button onclick="resetFilterData()" id="resetFilter"
                                            class="btn btn-sm d-none btn-danger text-white shadow-custom ml-2"
                                            type="button"><i class="fas fa-sync text-white"></i>Reset</button>
                                    </div>
                                </div>
                                <table class="table table-striped mb-0" id="addAssetData">
                                    <thead>
                                        <tr>
                                            <th width="50px" class="text-center pl-5">
                                                #
                                            </th>
                                            <th class="text-center">Kode Asset</th>
                                            <th>Deskripsi Asset</th>
                                            <th>Jenis Asset</th>
                                            <th>Type</th>
                                            <th>Lokasi Asset</th>
                                            <th>Kondisi Asset</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
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
