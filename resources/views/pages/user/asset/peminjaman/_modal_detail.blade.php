<div class="modal fade modalbox" id="modalDetailPeminjaman" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Peminjaman</h5>
                <a href="#" data-bs-dismiss="modal">Tutup</a>
            </div>
            <div class="modal-body py-0">
                <ul class="nav nav-tabs lined" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#detailPengadjuanTab" role="tab" aria-selected="true">
                            Detail Pengajuan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#requestPerpanjangan" role="tab" aria-selected="false">
                            Request Perpanjangan
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-2">
                    <div class="tab-pane fade active show" id="detailPengadjuanTab" role="tabpanel">
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Tanggal Peminjaman</strong></label>
                                <input type="text" name="" readonly value="2022/01/01" class="form-control" id="tanggalPeminjaman" placeholder="">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Tanggal Pengembalian</strong></label>
                                <input type="text" name="" readonly value="2022/01/01" class="form-control tanggalPengembalian" id="" placeholder="">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="mt-2" id="statusPeminjaman">
                            <span class="badge badge-success">Sedang Dipinjam</span>
                        </div>
                        <div class="containerDetailPeminjaman">

                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Alasan Peminjaman</strong></p>
                            <p id="alasanPeminjaman">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi, aperiam! Illum nobis ad sapiente debitis id? Non officia mollitia eum, cupiditate nemo unde, odio aliquam voluptatibus qui magni, fugiat harum?</p>
                        </div>
                        <div class="mt-2">
                            <p class="text-dark mb-1"><strong>Rating Pengembalian</strong></p>
                            <p id="rating" class="text-dark"></p>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="text-dark" for=""><strong>Keterangan Pengembalian</strong></label>
                                <p id="keteranganPengembalian"></p>
                            </div>
                        </div>
                        <hr>
                        <div class="containerPerpanjanganForm slide-bottom mt-3" style="display: none;">
                            <form action="" method="POST" class="form-submit" id="formPerpanjangan">
                                @csrf
                                <input type="hidden" name="tanggal_pengembalian" readonly value="" class="form-control tanggalPengembalian" id="" placeholder="">
                                <div class="text-center">
                                    <h4>Form Perpanjangan</h4>
                                </div>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="text-dark" for=""><strong>Tanggal Perpanjangan</strong></label>
                                        <input type="date" name="tanggal_expired_perpanjangan" class="form-control" id="" placeholder="Text Input">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="text-dark" for=""><strong>Alasan Perpanjangan</strong></label>
                                        <textarea name="alasan_perpanjangan" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mt-2 text-center mb-3">
                            <button onclick="showHideFormPerpanjangan(this)" style="display: none;" id="btnShowPerpanjangan" class="btn btn-warning border-radius-sm px-3" type="button">
                                <span class="">Ajukan Perpanjangan</span>
                            </button>
                            <button onclick="submitForm()" style="display: none;" id="btnSubmitPerpanjangan" class="btn btn-primary border-radius-sm px-3" type="button">
                                <span class="">Ajukan</span>
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="requestPerpanjangan" role="tabpanel">
                        <div class="containerPerpanjangan">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
