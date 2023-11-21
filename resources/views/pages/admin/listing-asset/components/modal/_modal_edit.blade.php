<div class="modal fade modalEditAsset" id="modalEdit" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.update', $asset->id) }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $asset->id }}" name="id">
                <div class="modal-body">
                    <div class="kt-scroll ps ps--active-y" data-scroll="true" style="overflow: hidden; height: 70vh;">
                        <div class="row">
                            <div class="form-group col-md-4 col-6">
                                <label for="">Kode Asset</label>
                                <input type="text" class="form-control" value="{{ $asset->kode_asset }}"
                                    name="kode_asset">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Deskripsi / Nama</label>
                                <input type="text" class="form-control" value="{{ $asset->deskripsi }}"
                                    name="deskripsi">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Seri</label>
                                <input type="text" value="{{ $asset->no_seri }}"
                                    @if (!auth()->user()->checkAccessEditAsset(auth()->user()->role)) readonly @endif class="form-control"
                                    name="no_seri">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Kelompok Aset</label>
                                <select name="id_group_asset" disabled class="form-control" id="groupAssetCreate">
                                    @if (isset($asset->kategori_asset->group_kategori_asset))
                                        <option selected="selected"
                                            value="{{ $asset->kategori_asset->group_kategori_asset->id }}">
                                            {{ $asset->kategori_asset->group_kategori_asset->nama_group }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Jenis Asset</label>
                                <select name="id_kategori_asset" disabled class="form-control" id="kategoriAssetCreate">
                                    @if (isset($asset->kategori_asset))
                                        <option selected="selected" value="{{ $asset->kategori_asset->id }}">
                                            {{ $asset->kategori_asset->nama_kategori }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nomor Urut</label>
                                <input type="text" value="{{ $asset->no_urut }}" class="form-control" name="no_urut">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Tanggal Perolehan</label>
                                <input type="text" disabled class="form-control"
                                    value="{{ $asset->tanggal_perolehan }}" readonly name="tanggal_perolehan">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Nilai Perolehan (Rp)</label>
                                <input type="number" class="form-control"
                                    @if (auth()->user()->role != 'admin' || auth()->user()->role != 'manager_asset' || auth()->user()->role != 'manager_it') readonly @endif
                                    value="{{ $asset->nilai_perolehan }}" name="nilai_perolehan">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Tanggal Pelunasan</label>
                                <input type="text" {{ Auth::user()->role == 'admin' ? 'readonly' : 'disabled' }}
                                    class="form-control datepickerCreate" name="tanggal_pelunasan"
                                    value="{{ $asset->tgl_pelunasan }}">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Vendor</label>
                                <select name="id_vendor" class="form-control" id="vendorAssetCreate">
                                    @if (isset($asset->vendor))
                                        <option selected="selected" value="{{ $asset->vendor->id }}">
                                            {{ $asset->vendor->nama_vendor }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Lokasi Asset</label>
                                <select name="id_lokasi" class="form-control" id="lokasiAssetEdit">

                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Ownership / Dipindahkan Ke</label>
                                <input type="text" disabled class="form-control"
                                    value="{{ $asset->owner_name }}">
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Satuan</label>
                                <select name="id_satuan_asset" class="form-control" id="satuanAssetCreate">
                                    @if (isset($asset->satuan_asset))
                                        <option selected="selected" value="{{ $asset->satuan_asset->id }}">
                                            {{ $asset->satuan_asset->nama_satuan }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-6">
                                <label for="">Jenis Perolehan</label>
                                <select class="form-control" name="jenis_penerimaan">
                                    <option>Pilih Jenis Perolehan</option>
                                    <option {{ $asset->jenis_penerimaan == 'PO' ? 'selected' : '' }} value="PO">PO
                                    </option>
                                    <option {{ $asset->jenis_penerimaan == 'Hibah Eksternal' ? 'selected' : '' }}
                                        value="Hibah Eksternal">Hibah Eksternal
                                    </option>
                                    <option {{ $asset->jenis_penerimaan == 'Hibah Penelitian' ? 'selected' : '' }}
                                        value="Hibah Penelitian">Hibah Penelitian
                                    </option>
                                    <option {{ $asset->jenis_penerimaan == 'Hibah Perorangan' ? 'selected' : '' }}
                                        value="Hibah Perorangan">Hibah Perorangan
                                    </option>
                                    <option {{ $asset->jenis_penerimaan == 'UMK' ? 'selected' : '' }}
                                        value="UMK">UMK
                                    </option>
                                    <option {{ $asset->jenis_penerimaan == 'CC' ? 'selected' : '' }}
                                        value="CC">CC
                                    </option>
                                    <option {{ $asset->jenis_penerimaan == 'Reimburse' ? 'selected' : '' }}
                                        value="Reimburse">Reimburse
                                    </option>
                                </select>
                            </div>
                            <div class="row ml-3">
                                <div class="form-group col-md-4 col-6">
                                    <div class="d-flex align-items-center mt-4">
                                        <span class="kt-switch kt-switch--sm kt-switch--icon">
                                            <label class="mb-0">
                                                <input type="checkbox" value="1"
                                                    {{ $asset->is_sparepart == 1 ? 'checked' : '' }}
                                                    name="is_sparepart">
                                                <span></span>
                                            </label>
                                        </span>
                                        <span class="ml-4">Sparepart</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <div class="d-flex align-items-center mt-4">
                                        <span class="kt-switch kt-switch--sm kt-switch--icon">
                                            <label class="mb-0">
                                                <input type="checkbox" {{ $asset->is_pinjam == 1 ? 'checked' : '' }}
                                                    value="1" name="is_pinjam">
                                                <span></span>
                                            </label>
                                        </span>
                                        <span class="ml-4">Dapat Dipinjam</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <div class="d-flex align-items-center mt-4">
                                        <span class="kt-switch kt-switch--sm kt-switch--icon">
                                            <label class="mb-0">
                                                <input type="checkbox" {{ $asset->is_it == 1 ? 'checked' : '' }}
                                                    value="1" name="is_it">
                                                <span></span>
                                            </label>
                                        </span>
                                        <span class="ml-4">Barang IT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label for="">Pilih Memorandum</label>
                                    <select name="status_memorandum" class="form-control mr-3" id=""
                                        onchange="changeMemorandumStatusEdit(this.value)">
                                        {{-- <option value="draft">Draft</option> --}}
                                        <option value="">Pilih Asal Memorandum</option>
                                        <option value="tidak-ada"
                                            {{ !isset($asset->id_surat_memo_andin) && !isset($asset->no_memo_surat) ? 'selected' : '' }}>
                                            Tidak Ada Memorandum
                                        </option>
                                        <option value="andin"
                                            {{ isset($asset->id_surat_memo_andin) && isset($asset->no_memo_surat) ? 'selected' : '' }}>
                                            Dari ANDIN
                                        </option>
                                        <option value="manual"
                                            {{ !isset($asset->id_surat_memo_andin) && isset($asset->no_memo_surat) ? 'selected' : '' }}>
                                            Input Manual
                                        </option>
                                    </select>
                                </div>
                                <div
                                    class="form-group {{ !isset($asset->id_surat_memo_andin) ? 'd-none' : '' }} memo_andin">
                                    <label for="">No Memorandum</label>
                                    <select name="id_surat_memo_andin" class="form-control memorandumAndin"
                                        id="">
                                        @if (isset($asset->id_surat_memo_andin))
                                            <option selected="selected" value="{{ $asset->id_surat_memo_andin }}">
                                                {{ $asset->no_memo_surat }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="noMemoSurat" name="no_memo_surat"
                                        value="{{ $asset->no_memo_surat }}">
                                </div>
                                <div
                                    class="form-group {{ isset($asset->id_surat_memo_andin) ? 'd-none' : '' }} memo_manual">
                                    <label for="">Nomor Memorandum</label>
                                    <input type="text" class="form-control" name="no_memo_surat_manual"
                                        value="{{ $asset->no_memo_surat }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor PO</label>
                                    <input type="text" class="form-control" value="{{ $asset->no_po }}"
                                        name="no_po">
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor SP3</label>
                                    <input type="text" class="form-control" value="{{ $asset->no_sp3 }}"
                                        name="no_sp3">
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label for="">Cost Center/Asset Holder</label>
                                    <input type="text" class="form-control" value="{{ $asset->cost_center }}"
                                        name="cost_center">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="">Call Center</label>
                                    <input type="text" class="form-control" name="call_center"
                                        value="{{ $asset->call_center }}">
                                </div> --}}
                                <div class="form-group">
                                    <label for="">Spesifikasi</label>
                                    <textarea name="spesifikasi" class="form-control" id="" cols="30" rows="10">{{ $asset->spesifikasi }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-group">
                                    <label for="">Nomor Akun</label>
                                    <select name="id_kelas_asset" class="form-control" id="kelasAssetCreate">
                                        @if (isset($asset->kelas_asset))
                                            <option selected="selected" value="{{ $asset->kelas_asset->id }}">
                                                {{ $asset->kelas_asset->nama_kelas }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="">Status Kondisi Asset</label>
                                        <div>
                                            <select disabled name="status_kondisi" class="form-control"
                                                style="width: 200px" id="">
                                                <option {{ $asset->status_kondisi == 'bagus' ? 'selected' : '' }}
                                                    value="bagus">Bagus
                                                </option>
                                                <option {{ $asset->status_kondisi == 'rusak' ? 'selected' : '' }}
                                                    value="rusak">Rusak
                                                </option>
                                                <option {{ $asset->status_kondisi == 'maintenance' ? 'selected' : '' }}
                                                    value="maintenance">Maintenance
                                                </option>
                                                <option
                                                    {{ $asset->status_kondisi == 'tidak-lengkap' ? 'selected' : '' }}
                                                    value="tidak-lengkap">Tidak Lengkap
                                                </option>
                                                <option
                                                    {{ $asset->status_kondisi == 'pengembangan' ? 'selected' : '' }}
                                                    value="pengembangan">Pengembangan
                                                </option>
                                                <option
                                                    {{ $asset->status_kondisi == 'tidak-ditemukan' ? 'selected' : '' }}
                                                    value="tidak-ditemukan">Tidak Ditemukan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="">Status Akunting Aset</label>
                                        <select name="status_akunting" disabled class="form-control"
                                            style="width: 60%;" id="">
                                            @foreach ($list_status as $key => $item)
                                                <option value="{{ $key }}"
                                                    {{ $key == $asset->status_akunting ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
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
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
