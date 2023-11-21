function btn_reload_fun(params) {
    $('.btn-reload-dt-table').click(function () {
        var table = $('.dt_table').DataTable();
        table.search('').columns().search('').draw();
    });
}
function btn_edit_fun(params) {
    $('.btn-edit').click(function (ev) {
        console.log('on click btn edit', ev);

        let mondal_name = $(this).data('modal-name');

        $('.form-group').children().removeClass('is-invalid');
        $('.invalid-feedback').remove();

        var href = $(this).data('href');
        $.ajax({
            url: href,
            success: function (data) {
                console.log('success:', data);

                $.each(data.field, function (field, value) {
                    if ($('#' + mondal_name + ' [name="' + field + '"]').attr('type') == 'checkbox') {
                        $('#' + mondal_name + ' [name="' + field + '"]').prop('checked', ('1' == value)).trigger('change');
                    }

                    if ($('#' + mondal_name + ' [name="' + field + '"]').is('select')) {
                        // if ($('#' + mondal_name +' [name="' + field + '"]').hasClass('select2')) {
                        //     let attr = $(this).attr('multiple');
                        //     if (typeof attr !== typeof undefined && attr !== false) {

                        //     }
                        // }

                        // if ($('#' + mondal_name +' [name="' + field + '"]').hasClass('select2')) {
                        //     console.log(true);
                        // }

                        // let attr = $(this).attr('multiple');
                        // if (typeof attr !== typeof undefined && attr !== false) {
                        //     $('#' + mondal_name +' [name="' + field + '"]').val(value).trigger('change');
                        // }
                        // else {
                        // }

                        $('#' + mondal_name + ' [name="' + field + '"]').val(value).trigger('change');
                        $('#' + mondal_name + ' [name="' + field + '"] option[value="' + value + '"]').prop('selected', true).trigger('change');
                    }
                    // else if ($('#' + mondal_name +' [name="' + field + '"]').is(':checkbox')) {
                    //     if (value == 'Aktif') {
                    //         $('#' + mondal_name +' [name="' + field + '"]').attr('checked', true);
                    //     }
                    // }
                    // else if ($('#' + mondal_name +' [name="' + field + '"]').is(':radio')) {
                    //     if (value == 'Include') {
                    //         $('#' + mondal_name +' input[value="' + value + '"][name="' + field + '"]').prop('checked', true);
                    //         $('#' + mondal_name +' input[value="Not Include"][name="' + field + '"]').prop('checked', false);
                    //     }
                    //     else if (value == 'Not Include') {
                    //         $('#' + mondal_name +' input[value="' + value + '"][name="' + field + '"]').prop('checked', true);
                    //         $('#' + mondal_name +' input[value="Include"][name="' + field + '"]').prop('checked', false);
                    //     }
                    // }
                    else if ($('#' + mondal_name + ' [name="' + field + '"]').prop('type') == 'file') {

                    } else {
                        $('#' + mondal_name + ' [name="' + field + '"]').val(value);
                    }

                    if (field == 'tagging') {
                        $('#' + mondal_name).find('.tagBox').tagging("add", value);
                    }

                    if (field == 'team') {
                        $('#' + mondal_name + ' [name="id_user[]"]').val(value).trigger('change');
                    }
                });

                $('#' + mondal_name + ' form').attr('action', data.action);
                if (data.extra) {
                    $('#' + mondal_name + ' form').data('extra', data.extra);
                }

                $('#' + mondal_name).modal('show');
                $('#' + mondal_name).modal('show');
            },
            error: function (a, b, c) {
                console.log('error:', a, b, c);
            },
        });
    });
}

function btn_delete_fun(params) {
    $('.btn-delete').click(function () {
        var token = $('meta[name="csrf-token"]').attr('content');
        var ini = $(this);
        // Swal.fire({
        //     title: 'Peringatan!',
        //     text: 'Are you sure? Data will be permanently deleted',
        //     // type: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, delete it!',
        //     cancelButtonText: 'No, cancel!'
        // }),function () {
        //     $.ajax({
        //         url: ini.data('href'),
        //         type: "DELETE",
        //         data: {
        //             _token: token,
        //         },
        //         success: function (data) {
        //             Swal.fire(
        //                 'Success',
        //                 'Data Anda berhasil dihapus.',
        //                 'success'
        //             )

        //             $('.dt_table').DataTable().ajax.reload();

        //             // ini.parents('tr').fadeOut('fast', function () {
        //             //     ini.parents('tr').remove();
        //             // });
        //         },
        //         error: function (request, status, error) {
        //             Swal.fire({
        //                 title: 'Peringatan!',
        //                 text: 'Terjadi kesalahan ' + request.responseText,
        //                 type: 'error'
        //             });
        //         },
        //     });
        // }

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan dihapus secara permanen",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CF4343',
            cancelButtonColor: '#d5d5d5',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ini.data('href'),
                    type: "DELETE",
                    data: {
                        _token: token,
                    },
                    success: function (data) {
                        if (data.feedback == true) {
                            if (data.emit) {
                                $.each(data.emit, function (index, value) {
                                    Livewire.emit(value);
                                });
                            }

                            Swal.fire(
                                'Success',
                                'Data Anda berhasil dihapus.',
                                'success'
                                )
                        }
                        else if (data.reload_page == true) {
                            Swal.fire(
                                'Success',
                                'Data Anda berhasil dihapus.',
                                'success'
                                )

                            location.reload();
                        }
                        else {
                            Swal.fire(
                                'Success',
                                'Data Anda berhasil dihapus.',
                                'success'
                                )

                            $('.dt_table').DataTable().ajax.reload();
                        }

                        // ini.parents('tr').fadeOut('fast', function () {
                        //     ini.parents('tr').remove();
                        // });
                    },
                    error: function (request, status, error) {
                        Swal.fire({
                            title: 'Peringatan!',
                            text: 'Terjadi kesalahan ' + request.responseText,
                            icon: 'error'
                        });
                    },
                });
            }
        })
    });
}

function funPrint(params) {
    $('.print').click(function () {
        let print_url = $(this).data('href');
        $.ajax({
            url: print_url,
            type: 'GET',
            success: function (data) {
                window.open(data.open);
            }
        });
    });
}

function showTooltip(params) {
    $('[data-toggle="tooltip"]').tooltip();
}

function _onChangeInputHasLampiranInsert(el) {
    var checked = $(el).is(':checked');
    if (checked) {
        $('#div_jml_halaman_insert').show();
        $('input[name="jml_halaman"]').val('');
        $('input[name="jml_halaman"]').focus();
    } else {
        $('#div_jml_halaman_insert').hide();
        $('input[name="jml_halaman"]').val('');
    }
}

function _onChangeInputHasLampiranEdit(el) {
    var checked = $(el).is(':checked');
    if (checked) {
        $('#div_jml_halaman_edit').show();
        $('input[name="jml_halaman"]').val('');
        $('input[name="jml_halaman"]').focus();
    } else {
        $('#div_jml_halaman_edit').hide();
        $('input[name="jml_halaman"]').val('');
    }
}

function _onChangeInputHasFileInsert(el){
    var fileName = $(el).val();
    $('#imgPreviewInputFileSurat1').hide();
    $('#imgPreviewInputFileSurat2').show();
    $('#delete_file_name_lampiran_surat').show();
    $('#file_name_lampiran_surat').html(fileName);
}

function _onDeleteInputHasFileInsert(){
    document.getElementById('inputFileSurat').value = null;
    $('#imgPreviewInputFileSurat1').show();
    $('#imgPreviewInputFileSurat2').hide();
    $('#delete_file_name_lampiran_surat').hide();
    $('#file_name_lampiran_surat').html("");
}

function _onChangeInputHasFileEdit(el){
    var fileName = $(el).val();
    $('#imgPreviewInputFileSuratEdit1').hide();
    $('#imgPreviewInputFileSuratEdit2').show();
    $('#delete_file_name_lampiran_surat_edit').show();
    $('#file_name_lampiran_surat_edit').html(fileName);
}

function _onDeleteInputHasFileEdit(){
    document.getElementById('inputFileSurat2').value = null;
    $('#imgPreviewInputFileSuratEdit1').show();
    $('#imgPreviewInputFileSuratEdit2').hide();
    $('#delete_file_name_lampiran_surat_edit').hide();
    $('#file_name_lampiran_surat_edit').html("");
}

$(document).ready(function () {
    // //Datepicker
    // $.fn.datepicker.defaults.format = "dd/M/yyyy";
    // $('.datepicker').datepicker({
    //     // startDate: '-3d'
    // });

    $('.btn-open-modal').click(function () {
        console.log('asdf');
        $('.form-group').children().removeClass('is-invalid');
        $('.invalid-feedback').remove();
        let mondal_name = $(this).data('modal-name');
        $('#' + mondal_name).modal('show');
    });

    $('.dt_table').on('draw.dt', function () {
        console.log('on draw dt');

        btn_edit_fun();
        btn_delete_fun();
        btn_reload_fun();
        showTooltip();
    });

    //select2
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    if (cekBrowser().isFirefox) {
        $('.modal').on('shown.bs.modal', function(e) {
            var tmpModal = $(e.target);
            var tmpSelect2List = tmpModal.find('.select2');
            $.each(tmpSelect2List, function(i, el) {
                if (undefined != $(el).select2) {
                    $(el).select2({
                        dropdownParent: tmpModal,
                    });
                }
            });
        });
    }

    //Print
    // funPrint();

    // Load data for the first time
    try {
        $('.dt_table').DataTable().ajax.reload();
    } catch (error) {
        console.log('error:', error);
    }
});
