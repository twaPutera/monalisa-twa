<div class="modal fade modalEditInventarisData" id="modalEditInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">List Asset Yang Akan Penghapusan Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="#" method="POST">
                @csrf
                <div class="modal-body">
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
                        <div class="alert alert-danger d-none" id="alert-list-asset">List asset dalam penghapusan asset wajib
                            diisi
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 editAssetData" id="editAssetData">
                                <thead>
                                    <tr>
                                        <th width="50px" class="text-center pl-5">
                                            #
                                        </th>
                                        <th class="text-center">Kode Asset</th>
                                        <th>Deskripsi Asset</th>
                                        <th>Jenis Asset</th>
                                        <th>Tipe</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
