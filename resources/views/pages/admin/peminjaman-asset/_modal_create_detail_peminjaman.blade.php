<div class="modal fade modalCreateDetailPeminjaman" id="modalDetailPemutihan" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Peminjaman Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.peminjaman.detail-asset.store-many') }}" method="POST">
                @csrf
                <input type="hidden" name="id_peminjaman_asset" value="{{ $peminjaman->id }}">
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="input-group mr-3" style="width: 250px;">
                            <input type="text" id="searchAsset" onkeyup="filterTableAsset()"
                                class="form-control form-control-sm" placeholder="Search for...">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-icon" onclick="filterTableAsset()" id="searchButton"
                                    type="button"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="">Kategori Asset</label>
                            <select name="id_kategori_asset" onchange="filterTableAsset()" class="form-control ml-3"
                                style="width: 200px" id="kategoriAssetFilter">
                                @foreach ($peminjaman->request_peminjaman_asset as $item)
                                    <option value="{{ $item->id_kategori_asset }}">
                                        {{ $item->kategori_asset->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <table class="table table-striped dt_table mb-0" id="addDetailPeminjaman">
                        <thead>
                            <tr>
                                <th width="50px" class="text-center pl-5">
                                    #
                                </th>
                                <th class="text-center">Kode Asset</th>
                                <th class="text-center">Deskripsi</th>
                                <th>Jenis Asset</th>
                                <th>Lokasi Asset</th>
                                <th>Nomor Seri</th>
                                <th width="150px">Kondisi Asset</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
