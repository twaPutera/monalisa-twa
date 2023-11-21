<div class="modal fade modalbox" id="modalDetailPengaduan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengaduan</h5>
                <a href="#" data-bs-dismiss="modal">Tutup</a>
            </div>
            <div class="modal-body py-0">
                <ul class="nav nav-tabs lined" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#detailPengaduanTab" role="tab"
                            aria-selected="true">
                            Detail Pengaduan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#logPengaduanTab" role="tab"
                            aria-selected="false">
                            Log Pengaduan
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-2">
                    <div class="tab-pane fade active show" id="detailPengaduanTab" role="tabpanel">
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Nama Asset</strong></p>
                            <p id="namaAsset"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Lokasi Asset</strong></p>
                            <p id="lokasiAsset"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Kelompok Asset</strong></p>
                            <p id="kelompokAsset"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Jenis Asset</strong></p>
                            <p id="jenisAsset"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Status Terakhir</strong></p>
                            <div id="statusTerakhir"></div>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Status Penghapusan Asset</strong></p>
                            <div id="statusPemutihan"></div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Tanggal Pengaduan</strong></label>
                                <input type="text" name="" readonly value="2022/01/01" class="form-control"
                                    id="tanggalPengaduan" placeholder="">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Prioritas</strong></p>
                            <div id="prioritas"></div>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Status Pengaduan</strong></p>
                            <div id="statusPengaduan"></div>
                        </div>
                        <div class="mt-2 d-none" id="gambarSaya">
                            <p class="text-dark mb-1"><strong>Gambar Pengaduan</strong></p>
                            <a href="" id="urlGambarSaya" download
                                class="btn btn-primary shadow-custom btn-sm mb-0"><i class="fa fa-download"></i>
                                Unduh
                                Gambar Pengaduan</a>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Catatan Pengaduan</strong></label>
                                <p id="catatanPengaduan"></p>
                            </div>
                        </div>
                        <div id="responPengaduan" class="d-none">
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="text-dark" for=""><strong>Catatan Respon
                                            Pengaduan</strong></label>
                                    <p id="catatanResponPengaduan"></p>
                                </div>
                            </div>
                            <div class="mt-2 d-none" id="gambarRespon">
                                <p class="text-dark mb-1"><strong>Gambar Respon Pengaduan</strong></p>
                                <a href="" id="urlGambarRespon" download
                                    class="btn btn-primary shadow-custom btn-sm mb-0"><i class="fa fa-download"></i>
                                    Unduh
                                    Gambar Respon Pengaduan</a>
                            </div>
                        </div>

                        <hr>
                        <div class="mt-2 text-center d-flex justify-content-center mb-3 d-none" id="editOrDeleteButton">
                            <form action="" class="form-submit" id="deletePengaduanButton" method="post">
                                @csrf
                                <button type="submit" class="btn btn-danger border-radius-sm px-3 me-2">
                                    <ion-icon name="trash"></ion-icon>
                                    <span class="">Hapus</span>
                                </button>
                            </form>
                            <a class="btn btn-success border-radius-sm px-3" href="" id="editPengaduanButton">
                                <ion-icon name="pencil"></ion-icon>
                                <span class="">Ubah</span>
                            </a>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="logPengaduanTab" role="tabpanel">
                        <div class="containerPerpanjangan" id="logContainer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
