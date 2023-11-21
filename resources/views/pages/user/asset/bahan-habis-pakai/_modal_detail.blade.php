<div class="modal fade modalbox" id="modalDetailBahanHabisPakai" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Permintaan Bahan Habis Pakai</h5>
                <a href="#" data-bs-dismiss="modal">Tutup</a>
            </div>
            <div class="modal-body py-0">
                <ul class="nav nav-tabs lined" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#detailPengaduanTab" role="tab"
                            aria-selected="true">
                            Detail Permintaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#logPengaduanTab" role="tab"
                            aria-selected="false">
                            Log Permintaan
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-2">
                    <div class="tab-pane fade active show" id="detailPengaduanTab" role="tabpanel">
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Nama Pengaju</strong></p>
                            <p id="namaPengaju"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Unit Kerja</strong></p>
                            <p id="unitKerja"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Jabatan</strong></p>
                            <p id="jabatan"></p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>No Memo</strong></p>
                            <p id="noMemo"></p>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Tanggal Pengambilan</strong></label>
                                <input type="text" name="" readonly value="2022/01/01" class="form-control"
                                    id="tanggalPengambilan" placeholder="">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="containerDetailPeminjaman">

                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Status Permintaan</strong></p>
                            <div id="statusPermintaan"></div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Alasan Permintaan</strong></label>
                                <p id="alasanPermintaan"></p>
                            </div>
                        </div>

                        <hr>
                        <div class="mt-2 text-center d-flex justify-content-center mb-3 d-none" id="editOrDeleteButton">
                            <a class="btn btn-success border-radius-sm px-3" href="" id="editPermintaanButton">
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
