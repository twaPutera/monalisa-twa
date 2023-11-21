<div class="modal fade modalCreatePemindahan" id="modalCreatePemindahan" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Pindahkan Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.pemindahan-asset.store') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $asset->id }}" name="asset_id">
                <div class="modal-body row">
                    <div class="col-md-4 col-12">
                        <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
                            <table id="tableProperti" class="table table-striped">
                                <tr>
                                    <td width="40%">Deskripsi</td>
                                    <td><strong>{{ $asset->deskripsi }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Asset Group</td>
                                    <td><strong>{{ $asset->kategori_asset->group_kategori_asset->nama_group ?? 'Tidak Ada Group' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Kategori</td>
                                    <td><strong>{{ $asset->kategori_asset->nama_kategori ?? 'Tidak Ada Kategori' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Nilai perolehan</td>
                                    <td><strong>{{ $asset->nilai_perolehan }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Tgl. Perolehan</td>
                                    <td><strong>{{ $asset->tanggal_perolehan }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Tgl. Pelunasan</td>
                                    <td><strong>{{ $asset->tgl_pelunasan }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Jenis Penerimaan</td>
                                    <td><strong>{{ $asset->jenis_penerimaan }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Satuan</td>
                                    <td><strong>{{ $asset->satuan_asset->nama_satuan ?? 'Tidak Ada Satuan' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">No. Surat / Memo</td>
                                    <td><strong>{{ $asset->no_memo_surat ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">No. PO</td>
                                    <td><strong>{{ $asset->no_po ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">No. SP3</td>
                                    <td><strong>{{ $asset->no_sp3 ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">No. Seri</td>
                                    <td><strong>{{ $asset->no_seri ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Kode Akun</td>
                                    <td><strong>{{ $asset->kelas_asset->no_akun ?? '-' }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="">No Surat BAST</label>
                            <input type="text" class="form-control" name="no_bast">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Pemindahan</label>
                            <input type="text" value="{{ date('Y-m-d') }}" class="form-control datepickerCreate"
                                readonly name="tanggal_pemindahan">
                        </div>
                        <div class="form-group">
                            <label for="">Penanggung Jawab Sebelumnya</label>
                            <input type="text" value="{{ $asset->owner_name }}" readonly class="form-control"
                                name="penyerah_asset_nama">
                            <input type="hidden" value="{{ $asset->ownership }}" class="form-control"
                                name="penyerah_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Jabatan Penanggung Jawab Sebelumnya</label>
                            <input type="text" value="{{ $asset->owner->jabatan ?? "" }}" class="form-control" name="jabatan_penyerah">
                            {{-- <select name="jabatan_penyerah" onchange="getUnitByPosition(this, 'unitKerjaPenyerahSelect')" class="form-control" id="positionPenyerahSelect">

                            </select> --}}
                        </div>
                        <div class="form-group">
                            <label for="">Unit Kerja Penanggung Jawab Sebelumnya</label>
                            <input type="text" value="{{ $asset->owner->unit_kerja ?? "" }}" class="form-control" name="unit_kerja_penyerah">
                            {{-- <select name="unit_kerja_penyerah" class="form-control" id="unitKerjaPenyerahSelect">

                            </select> --}}
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="">Penanggung Jawab Selanjutnya</label>
                            <select name="penerima_asset" class="form-control" id="newOwnership">

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Jabatan Penanggung Jawab Selanjutnya</label>
                            <input type="text" value="" class="form-control" id="jabatanPenerima" name="jabatan_penerima">
                            {{-- <select name="jabatan_penerima" onchange="getUnitByPosition(this, 'unitKerjaPenerimaSelect')" class="form-control" id="positionPenerimaSelect">

                            </select> --}}
                        </div>
                        <div class="form-group">
                            <label for="">Unit Kerja Penanggung Jawab Selanjutnya</label>
                            <input type="text" value="" class="form-control" id="unitKerjaPenerima" name="unit_kerja_penerima">
                            {{-- <select name="unit_kerja_penerima" class="form-control" id="unitKerjaPenerimaSelect">

                            </select> --}}
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
