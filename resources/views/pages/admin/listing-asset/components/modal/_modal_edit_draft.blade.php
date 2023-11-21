<div class="modal fade modalEditDraftAsset" id="modalEditDraftAsset" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="idAssetDraft">
                <div class="modal-body">
                    <div class="kt-scroll ps ps--active-y" data-scroll="true" style="overflow: hidden; height: 70vh;">
                        <div class="row">
                            <div class="form-group col-md-4 col-6">
                                <label for="">Kode Asset</label>
                                <input type="text" class="form-control" name="kode_asset">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Deskripsi / Nama</label>
                                <input type="text" class="form-control" name="deskripsi">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Seri</label>
                                <input type="text" class="form-control" name="no_seri">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Kelompok Aset</label>
                                <select name="id_group_asset" class="form-control" id="groupAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Jenis Asset</label>
                                <select name="id_kategori_asset" class="form-control"
                                    onchange="getNoUrutByKelompok(this)" id="kategoriAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Urut</label>
                                <input type="text" class="form-control" name="no_urut">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Tanggal Perolehan</label>
                                <input type="text" class="form-control datepickerCreate" readonly
                                    name="tanggal_perolehan">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nilai Perolehan (Rp)</label>
                                <input type="number" class="form-control" name="nilai_perolehan">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Tanggal Pelunasan</label>
                                <input type="text" class="form-control datepickerCreate" readonly
                                    name="tanggal_pelunasan">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Vendor</label>
                                <select name="id_vendor" class="form-control" id="vendorAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Lokasi Asset</label>
                                <select name="id_lokasi" class="form-control" id="lokasiAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Asset Holderr</label>
                                <select name="ownership" class="form-control" id="ownershipAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Satuan</label>
                                <select name="id_satuan_asset" class="form-control" id="satuanAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Jenis Perolehan</label>
                                <select class="form-control" onchange="jenisAssetChangeEdit(this)"
                                    name="jenis_penerimaan">
                                    <option selected="">Pilih Jenis Perolehan</option>
                                    <option data-asset-lama="0" value="PO">PO</option>
                                    <option data-asset-lama="0" value="Hibah Eksternal">Hibah Eksternal</option>
                                    <option data-asset-lama="0" value="Hibah Penelitian">Hibah Penelitian</option>
                                    <option data-asset-lama="0" value="Hibah Perorangan">Hibah Perorangan</option>
                                    <option data-asset-lama="1" value="Dari Asset Lama">Dari Asset Lama</option>
                                    <option data-asset-lama="0" value="UMK">UMK</option>
                                    <option data-asset-lama="0" value="CC">CC</option>
                                    <option data-asset-lama="0" value="Reimburse">Reimburse</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Seri</label>
                                <input type="text" class="form-control" name="no_seri">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Urut</label>
                                <input type="text" onkeyup="generateKodeAsset(this)" class="form-control"
                                    name="no_urut">
                            </div>
                            {{-- <div class="form-group col-md-4 col-6">
                                <label for="">Nilai Buku (Rp)</label>
                                <input type="number" class="form-control" name="nilai_buku_asset">
                            </div> --}}
                            <div class="form-group col-md-4 col-6" id="asal-asset-container-edit"
                                style="display: none;">
                                <label for="">Asal Asset</label>
                                <input type="text" class="form-control" readonly id="asal_asset_preview">
                                <input type="hidden" class="form-control" name="asal_asset" id="asal_asset_id">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-md-4 col-6 d-flex">
                                        <div class="d-flex align-items-center mr-4">
                                            <span class="kt-switch kt-switch--sm kt-switch--icon">
                                                <label class="mb-0">
                                                    <input type="checkbox" value="1" name="is_sparepart">
                                                    <span></span>
                                                </label>
                                            </span>
                                            <span class="ml-4">Sparepart</span>
                                        </div>
                                        <div class="d-flex align-items-center mr-4">
                                            <span class="kt-switch kt-switch--sm kt-switch--icon">
                                                <label class="mb-0">
                                                    <input type="checkbox" value="1" name="is_pinjam">
                                                    <span></span>
                                                </label>
                                            </span>
                                            <span class="ml-4">Dapat Dipinjam</span>
                                        </div>
                                        <div class="d-flex align-items-center mr-4">
                                            <span class="kt-switch kt-switch--sm kt-switch--icon">
                                                <label class="mb-0">
                                                    <input type="checkbox" value="1" name="is_it">
                                                    <span></span>
                                                </label>
                                            </span>
                                            <span class="ml-4">Barang IT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                {{-- <div class="form-group">
                                    <label for="">No Memorandum</label>
                                    <select name="id_surat_memo_andin" class="form-control memorandumAndin"
                                        id="">

                                    </select>
                                    <input type="hidden" id="noMemoSurat" name="no_memo_surat" value="">
                                </div> --}}
                                <div class="form-group">
                                    <label for="">Pilih Memorandum</label>
                                    <select name="status_memorandum" class="form-control mr-3" id=""
                                        onchange="changeMemorandumStatusEdit(this.value)">
                                        {{-- <option value="draft">Draft</option> --}}
                                        <option value="">Pilih Asal Memorandum</option>
                                        <option value="tidak-ada">Tidak Ada Memorandum</option>
                                        <option value="andin">Dari ANDIN</option>
                                        <option value="manual">Input Manual</option>
                                    </select>
                                </div>
                                <div class="form-group d-none memo_andin">
                                    <label for="">No Memorandum</label>
                                    <select name="id_surat_memo_andin" class="form-control memorandumAndin"
                                        id="">

                                    </select>
                                    <input type="hidden" class="noMemoSurat" name="no_memo_surat" value="">
                                </div>
                                <div class="form-group d-none memo_manual">
                                    <label for="">Nomor Memorandum</label>
                                    <input type="text" class="form-control" name="no_memo_surat_manual">
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor PO</label>
                                    <input type="text" class="form-control" name="no_po">
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor SP3</label>
                                    <input type="text" class="form-control" name="no_sp3">
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label for="">Cost Center</label>
                                    <input type="text" class="form-control" name="cost_center">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="">Call Center</label>
                                    <input type="text" class="form-control" name="call_center">
                                </div> --}}
                                <div class="form-group">
                                    <label for="">Spesifikasi</label>
                                    <textarea name="spesifikasi" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label for="">Nomor Akun</label>
                                    <select name="id_kelas_asset" class="form-control" id="kelasAssetEdit">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="">Status Kondisi Aset</label>
                                        <select name="status_kondisi"
                                            onchange="changeStatusKondisiAsset(this.value,'status_akunting_edit')"
                                            class="form-control mr-3 status_kondisi" style="width: 60%;"
                                            id="status_kondisi_edit">
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
                                        <label for="">Status Akunting Aset</label>
                                        <select name="status_akunting" class="form-control mr-3 status_akunting"
                                            style="width: 60%;" id="status_akunting_edit">
                                            @foreach ($list_status as $key => $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar Asset Saat Ini</label>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <img src="" id="preview-file-image" alt="Asset Image"
                                            width="150px">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span id="preview-file-text-edit">No File
                                                Choosen</span> <br>
                                            <span id="preview-file-error-edit" class="text-danger"></span>
                                        </div>
                                        <label for="gambar_asset_edit" class="btn btn-primary">
                                            Upload
                                            <input type="file" id="gambar_asset_edit"
                                                accept=".jpeg,.png,.jpg,.gif,.svg" class="d-none"
                                                name="gambar_asset">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
