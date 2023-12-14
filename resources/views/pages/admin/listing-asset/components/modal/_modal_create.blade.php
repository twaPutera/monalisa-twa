<div class="modal fade modalCreateAsset" id="modalCreate" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.store') }}" method="POST">
                @csrf
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
                                <select name="id_group_asset" class="form-control" id="groupAssetCreate">
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Jenis Asset</label>
                                <select name="id_kategori_asset" onchange="getNoUrutByKelompok(this)"
                                    class="form-control" id="kategoriAssetCreate">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Urut</label>
                                <input type="text" class="form-control" onkeyup="generateKodeAsset(this)"
                                    name="no_urut">
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
                                <input type="text" class="form-control datepickerCreate"
                                    name="tanggal_pelunasan">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Vendor</label>
                                <select name="id_vendor" class="form-control" id="vendorAssetCreate">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Lokasi Asset</label>
                                <select name="id_lokasi" class="form-control" id="lokasiAssetCreate">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Fungsi</label>
                                <select name="unit_kerja" class="form-control" id="unit_kerja">
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Jenis Perolehan</label>
                                <!-- bagian ini telah diupdate oleh wahyu -->
                                <select class="form-control" onchange="jenisAssetChange(this)" name="jenis_penerimaan">
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
                                <label for="">Satuan</label>
                                <select name="id_satuan_asset" class="form-control" id="satuanAssetCreate">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Asset Holder</label>
                                <select name="ownership" class="form-control" id="ownershipAssetCreate">
                                    
                                </select>
                            </div>
                           
                            {{-- <div class="form-group col-md-4 col-6">
                                <label for="">Nilai Buku (Rp)</label>
                                <input type="number" class="form-control" name="nilai_buku_asset">
                            </div> --}}
                            <div class="form-group col-md-4 col-6" id="asal-asset-container" style="display: none;">
                                <label for="">Asal Asset</label>
                                <input type="text" class="form-control" readonly id="asal_asset_preview_edit">
                                <input type="hidden" class="form-control" name="asal_asset"
                                    id="asal_asset_id_edit">
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
                                <div class="form-group">
                                    <label for="">Pilih Memorandum</label>
                                    <select name="status_memorandum" class="form-control mr-3" id=""
                                        onchange="changeMemorandumStatus(this.value)">
                                        {{-- <option value="draft">Draft</option> --}}
                                        <option value="">Pilih Asal Memorandum</option>
                                        <option value="tidak-ada">Tidak Ada Memorandum</option>
                                        <option value="andin">Dari ANDIN</option>
                                        <option value="manual">Input Manual</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="memo_andin">
                                    <label for="">No Memorandum</label>
                                    <select name="id_surat_memo_andin" class="form-control memorandumAndin"
                                        id="">

                                    </select>
                                    <input type="hidden" id="noMemoSurat" name="no_memo_surat" value="">
                                </div>
                                <div class="form-group d-none" id="memo_manual">
                                    <label for="">Nomor Memorandum</label>
                                    <input type="text" class="form-control" name="no_memo_surat_manual">
                                </div>
                                <div class="form-group" id="twa_nomor_po">
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
                                    <select name="id_kelas_asset" class="form-control" id="kelasAssetCreate">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="">Status Kondisi Aset</label>
                                        <select name="status_kondisi"
                                            onchange="changeStatusKondisiAsset(this.value, 'status_akunting')"
                                            class="form-control mr-3 status_kondisi" style="width: 60%;"
                                            id="status_kondisi">
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
                                        <label for="status_akunting">Status Akunting Aset</label>
                                        <select name="status_akunting" class="form-control mr-3 status_akunting"
                                            style="width: 60%;" id="status_akunting">
                                            @foreach ($list_status as $key => $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar Asset</label>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span id="preview-file-text">No File Choosen</span> <br>
                                            <span id="preview-file-error" class="text-danger"></span>
                                        </div>
                                        <label for="gambar_asset" class="btn btn-primary">
                                            Upload
                                            <input type="file" id="gambar_asset"
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
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- <script>
    function handleOwnershipChange() {
        var ownershipSelect = document.getElementById("ownershipAssetCreate");
        var unitKerjaSelect = document.getElementById("unit_kerja");

        if (ownershipSelect.value !== "") {
            unitKerjaSelect.disabled = true;
        } else {
            unitKerjaSelect.disabled = false;
        }
    }

    function handleUnitKerjaChange() {
        var ownershipSelect = document.getElementById("ownershipAssetCreate");
        var unitKerjaSelect = document.getElementById("unit_kerja");

        if (unitKerjaSelect.value !== "") {
            ownershipSelect.disabled = true;
        } else {
            ownershipSelect.disabled = false;
        }
    }
</script> -->


<script>
    var initialOwnershipState;
    var initialUnitKerjaState;

    document.addEventListener("DOMContentLoaded", function () {
        // Save the initial state of the selects when the page loads
        initialOwnershipState = document.getElementById("ownershipAssetCreate").value;
        initialUnitKerjaState = document.getElementById("unit_kerja").value;
    });

    function handleOwnershipChange() {
        var ownershipSelect = document.getElementById("ownershipAssetCreate");
        var unitKerjaSelect = document.getElementById("unit_kerja");

        if (ownershipSelect.value !== "" && unitKerjaSelect.value != 0) {
            unitKerjaSelect.disabled = true;
        } else {
            unitKerjaSelect.disabled = false;
        }
    }

    function handleUnitKerjaChange() {
        var ownershipSelect = document.getElementById("ownershipAssetCreate");
        var unitKerjaSelect = document.getElementById("unit_kerja");
        //alert(unitKerjaSelect.value);
        if (unitKerjaSelect.value !== "" && unitKerjaSelect.value != 0) {
            ownershipSelect.disabled = true;
        }else{
            ownershipSelect.disabled = false;
        }
    }

    function resetForm() {
        var ownershipSelect = document.getElementById("ownershipAssetCreate");
        var unitKerjaSelect = document.getElementById("unit_kerja");

        // Reset selects to their initial state
        //ownershipSelect.value = "2";
        //unitKerjaSelect.value = "2";
        //unitKerjaSelect.selectedIndex = -1;

        // Enable both selects
        ownershipSelect.disabled = false;
        unitKerjaSelect.disabled = false;
    }
</script>
