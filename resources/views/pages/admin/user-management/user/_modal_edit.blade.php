<div class="modal fade modalEdituser" id="modalCreate" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control" name="email">
                    </div>
                    <div class="form-group">
                        <label for="">Username SSO</label>
                        <input type="text" class="form-control" name="username_sso">
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" class="form-control" id="">
                            <option value="user">User Aset</option>
                            <option value="admin">Admin Aset</option>
                            <option value="staff_asset">Staff Aset</option>
                            <option value="staff_it">Staff IT</option>
                            <option value="manager_asset">Manager Aset</option>
                            <option value="manager_it">Manager IT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Unit Kerja</label>
                        <input type="text" class="form-control" name="unit_kerja">
                    </div>
                    <div class="form-group">
                        <label for="">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan">
                    </div>
                    <div class="form-group">
                        <span class="kt-switch kt-switch--sm">
                            <label>
                                <input type="checkbox" value="1" checked="checked" name="status">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
