$(".form-submit").submit(function (e) {
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    let url = form.attr("action");
    let method = form.attr("method");
    let enctype = form.attr("enctype");
    $(this)
        .children("input")
        .each(function () {
            if ($(this).attr("type") == "file") {
                if ($(this).attr("multiple")) {
                    for (let i = 0; i < $(this)[0].files.length; i++) {
                        formData.append(
                            $(this).attr("name"),
                            $(this)[0].files[i]
                        );
                    }
                } else {
                    formData.append($(this).attr("name"), $(this)[0].files[0]);
                }
            } else if ($(this).attr("type") == "checkbox") {
                let arrayChecked = [];
                $('input[name="' + $(this).attr("name") + '"]:checked').each(
                    function () {
                        arrayChecked.push($(this).val());
                    }
                );
                if ($(this).is(":checked")) {
                    formData.append($(this).attr("name"), arrayChecked);
                }
            } else if ($(this).attr("type") == "radio") {
                if ($(this).is(":checked")) {
                    formData.append($(this).attr("name"), $(this).val());
                }
            } else {
                formData.append($(this).attr("name"), $(this).val());
            }
        });

    $.ajax({
        url: url,
        type: method,
        enctype: enctype,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            $(".backdrop").show();
            $(".loadingSpiner").show();
        },
        success: function (response) {
            $(".backdrop").hide();
            $(".loadingSpiner").hide();
            if (response.success) {
                // let fileinput = form.find(".fileinput");
                // console.log(fileinput);
                // if (fileinput.length > 0) {
                //     fileinput.fileinput("clear");
                // }
                // showToaster(response.message, 'Berhasil');
            } else {
                console.log(response);
                // showToaster(response.error, "Error");
            }
            $("body").trigger("_EventAjaxSuccess", [form, response]);
        },
        error: function (response) {
            $(".backdrop").hide();
            $(".loadingSpiner").hide();
            let errors;
            if (response.status == 500) {
                errors = response.responseJSON;
            } else if (response.status == 422) {
                errors = response.responseJSON.errors;
            } else if (response.status == 400) {
                errors = response.responseJSON.errors;
            }
            $("body").trigger("_EventAjaxErrors", [form, errors]);
            // for (let key in errors) {
            //     let element = form.find(`[name=${key}]`);
            //     clearValidation(element);
            //     showValidation(element, errors[key][0]);
            // }
        },
    });
});

const formConfirmSubmit = () => {
    $(".form-confirm").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.value) {
                let formData = new FormData(form[0]);
                let url = form.attr("action");
                let method = form.attr("method");
                let enctype = form.attr("enctype");
                $.ajax({
                    url: url,
                    type: method,
                    enctype: enctype,
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $(".backdrop").show();
                    },
                    success: function (response) {
                        $(".backdrop").hide();
                        if (response.success) {
                            $("body").trigger("_EventAjaxSuccess", [
                                form,
                                response,
                            ]);
                        } else {
                            console.log(response);
                            // showToaster(response.error, "Error");
                        }
                    },
                    error: function (response) {
                        // console.log(response)
                        $(".backdrop").hide();
                        $(".loadingSpiner").hide();
                        let errors;
                        if (response.status == 500) {
                            errors = response.responseJSON;
                        } else if (response.status == 422) {
                            errors = response.responseJSON.errors;
                        } else if (response.status == 400) {
                            errors = response.responseJSON.errors;
                        }
                        $("body").trigger("_EventAjaxErrors", [form, errors]);
                    },
                });
            }
        });
    });
};

const showAlert = (title, message, type) => {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        confirmButtonText: "Ok",
    });
};

$(document).ready(function () {
    $(".dt_table").on("draw.dt", function () {
        formConfirmSubmit();
    });
    formConfirmSubmit();
});

const showValidation = (element, message) => {
    $(element).addClass("is-invalid");
    $(element)
        .parent()
        .append(`<div class="invalid-feedback">${message}</div>`);
};

const clearValidation = (element) => {
    $(element).removeClass("is-invalid");
    $(element).parent().find(".invalid-feedback").remove();
};

$(".modal").on("hidden.bs.modal", function (e) {
    let modal = $(this);
    modal.find(".invalid-feedback").remove();
    modal.find(".is-invalid").removeClass("is-invalid");
});

$('.inputimage input[type="file"').change(function () {
    let file = $(this)[0].files[0];
    let reader = new FileReader();
    reader.onload = function () {
        $(".inputimage img").attr("src", reader.result);
    };
    reader.readAsDataURL(file);
});

const openModalByClass = (classname) => {
    $(`.${classname}`).modal("show");
};

const arrayMonth = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];

const formatDateIntoIndonesia = (data) => {
    let date = new Date(data);
    let day = date.getDate();
    let month = date.getMonth();
    let year = date.getFullYear();
    return `${day} ${arrayMonth[month]} ${year}`;
};

const formatDateTimeIntoIndonesia = (data) => {
    let date = new Date(data);
    let day = date.getDate();
    let month = date.getMonth();
    let year = date.getFullYear();
    let hour = date.getHours();
    let minute = date.getMinutes();
    return `${day} ${arrayMonth[month]} ${year} pada jam ${hour}:${minute}`;
};

const sumDiffFromTwoDate = (date1, date2) => {
    let diff = date1.getTime() - date2.getTime();
    return Math.ceil(diff / (1000 * 3600 * 24));
};

const sumDiffAndTimeFromTwoDate = (date1, date2) => {
    var diff = date1.getTime() - date2.getTime();

    days = Math.floor(diff / 1000 / 60 / 60 / 24);
    diff -= days * 1000 * 60 * 60 * 24;

    hours = Math.floor(diff / 1000 / 60 / 60);
    diff -= hours * 1000 * 60 * 60;

    minutes = Math.floor(diff / 1000 / 60);
    diff -= minutes * 1000 * 60;

    seconds = Math.floor(diff / 1000);

    //  Set Duration
    var sDuration = days + " Hari " + hours + " Jam " + minutes + " Menit";
    //  -------------------------------------------------------------------  //
    return sDuration;
};

const formatNumber = (number) => {
    let numberString = number.toString().replace(".", ",");
    return numberString.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
};
