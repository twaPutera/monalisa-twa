function file_image_validation(params) {
    $('.image_file').change(function (event) {
        var ini      = $(this);
        var file     = this.files[0];
        var fileType = file.type;
        var match    = ["image/jpeg", "image/png", "image/jpg"];
        if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))) {
            ini.val('');

            Swal.fire({
                title: 'Peringatan!',
                html: 'Jenis File Tidak Mendukung !<br>Please Use File Type : [jpg, jpeg, png]',
                icon: 'warning',
            })

            return false;
        }
        else {
            var iSize = (ini[0].files[0].size / 1024);

            if (iSize / 1024 > 1) {
                if (((iSize / 1024) / 1024) > 1) {
                    iSize = (Math.round(((iSize / 1024) / 1024) * 100) / 100);

                    Swal.fire({
                        title: 'Peringatan!',
                        html: 'Ukuran File lebih dari 5 Mb.<br> Ukuran File = ' + iSize + ' Gb',
                        icon: 'warning',
                    })

                    ini.val('');
                }
                else {
                    iSize = (Math.round((iSize / 1024) * 100) / 100);

                    if (iSize >= 5) {
                        Swal.fire({
                            title: 'Peringatan!',
                            html: 'Ukuran File lebih dari 5 Mb.<br> Ukuran File = ' + iSize + ' Mb',
                            icon: 'warning',
                        })

                        ini.val('');
                    }
                }
            }
        }
    });
}

function form_send(params) {
    $('.form-send').submit(function (e) {
        console.log('form_send:', e);

        $('#progress-modal').modal({ backdrop: 'static', keyboard: false });

        e.preventDefault();
        var ini = $(this);
        var el = $('#submit');
        el.prop('disabled', true);

        var form = $(this);
        var formData = new FormData();
        var formParams = form.serializeArray();

        var tmpExtra = {};
        if (form.data('extra')) {
            tmpExtra = form.data('extra');
        }
        console.log('tmpExtra:', tmpExtra);
        console.log('keyExtra:', window._keyExtra);

        var urlAction = '';
        if (tmpExtra[window._keyExtra]) {
            urlAction = tmpExtra[window._keyExtra];
        }
        console.log('urlAction:', urlAction);

        if (urlAction) {
            form.attr('action', urlAction);
        }

        // Input file
        $.each(form.find('input[type="file"]'), function (i, tag) {
            $.each($(tag)[0].files, function (i, file) {
                formData.append(tag.name, file);
            });
        });

        // Input text
        $.each(formParams, function (i, val) {
            formData.append(val.name, val.value);
        });

        // Input checkbox
        $.each(form.find('input[type="checkbox"]'), function (i, tag) {
            formData.set(tag.name, ($(tag).is(':checked')) ? '1' : '0');
        });

        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".saving-progress-bar").width(percentComplete + '%');
                        $(".saving-progress-bar").html(percentComplete + '%');
                    }
                }, false);
                // $('#progress-modal').modal('hide');
                return xhr;
            },
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            // async: false,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                ini.parents('div.modal').modal('hide');
                $('#progress-modal').modal('show');
                $(".saving-progress-bar").width('0%');
            },
            // complete: function () {
            //     $('#progress-modal').modal('hide');
            // },
            success: function (data) {
                setTimeout(function () {
                    $('#progress-modal').modal('hide');
                }, 500);

                setTimeout(function () {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                    else if (data.notif == true) {
                        $('.dt_table').DataTable().ajax.reload();

                        ini[0].reset();

                        $('.select2').val('').trigger('change');

                        Swal.fire(
                            'Sukses',
                            'Data berhasil disimpan.',
                            'success'
                        )

                        ini.parents('div.modal').modal('hide');
                        // $('#modal-edit').modal('hide');

                        $('.form-group').children().removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        el.prop('disabled', false);
                    }
                    else if (data.feedback == true) {
                        ini[0].reset();

                        $('.select2').val('').trigger('change');

                        if (data.emit) {
                            $.each(data.emit, function (index, value) {
                                Livewire.emit(value);
                            });
                        }

                        if (data.message) {
                            Swal.fire(
                                'Warning',
                                data.message,
                                'warning'
                            )
                        }
                        else {
                            Swal.fire(
                                'Sukses',
                                'Data berhasil disimpan.',
                                'success'
                            )
                        }

                        ini.parents('div.modal').modal('hide');
                        // $('#modal-edit').modal('hide');

                        $('.form-group').children().removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        el.prop('disabled', false);
                    }
                    else if (data.reload_page == true) {
                        Swal.fire(
                            'Sukses',
                            'Data berhasil disimpan.',
                            'success'
                        )

                        location.reload();
                    }
                }, 1000);
            },
            error: function (request, status, error) {
                setTimeout(function () {
                    $('#progress-modal').modal('hide');
                }, 500);

                setTimeout(function () {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Data Gagal Disimpan! ' + request.responseText,
                        icon: 'error'
                    });

                    var json = JSON.parse(request.responseText);
                    $('.form-group').children().removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    $.each(json.errors, function (key, value) {
                        $('.form-send [name="' + key + '"]').parents('.form-group').children().addClass('is-invalid');
                        $('.form-send [name="' + key + '"]').after('<span class="invalid-feedback">' + value + '</span>');
                    });

                    el.prop('disabled', false);
                }, 1000);
            }
        });
        return false;
    });
}

$(document).ready(function () {
    //Number format
    $('.price').number(true, 2, '.', ',');

    //date now
    let today = new Date().toISOString().substr(0, 10);
    $('.today').val(today);

    //File Validation
    file_image_validation();

    form_send();
});