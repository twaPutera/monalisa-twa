<script>
    $('.modalCreateAssetService').on('shown.bs.modal', function() {
        setTimeout(() => {
            // generateSelect2Lokasi('lokasiAssetCreateService');
            generateAssetSelect2Create('listAssetLocation', 'root');
            generateSelect2KategoriService('kategoriServiceCreate');
            generateAssetServiceDateSelect2Create('listAssetServicesDate');
            $('#listAssetLocationPerencanaan').select2({
                width: '100%',
                placeholder: 'Pilih Asset',
                allowClear: true,
                parent: $(this)
            });

            $('#lokasiAssetCreateService').select2({
                width: '100%',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
                parent: $(this)
            });

            $('#lokasiAssetCreateServicePerencanaan').select2({
                width: '100%',
                placeholder: 'Pilih Lokasi',
                allowClear: true,
                parent: $(this)
            });
        }, 2000);
    });
    $('#lokasiAssetCreateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocation', $(this).val());
    });

    $('#listAssetServicesDate').on('change', function() {
        generateAssetFromPerencanaan($(this).val());
        generateLokasiFromPerencanaan($(this).val());
    });

    $('#lokasiAssetUpdateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocationUpdate', $(this).val());
    });

    $('#listAssetLocation').on('change', function() {
        // generateSelect2Lokasi('lokasiAssetCreateService');
    });

    const generateAssetFromPerencanaan = (value) => {
        $.ajax({
            url: "{{ route('admin.listing-asset.get-all-data-asset-select2') }}",
            type: 'GET',
            dataType: 'json',
            data:{
                id_asset: value,
            },
            success: function(response) {
                if (response.success) {
                    const select = $('#listAssetLocationPerencanaan');
                    select.empty();
                    response.data.forEach((item) => {
                        select.append(
                            `<option value="${item.id}" selected>${item.text}</option>`);
                    });
                }
            }
        })
    }
    const generateLokasiFromPerencanaan = (value) => {
        $.ajax({
            url: "{{ route('admin.setting.lokasi.get-select2') }}",
            type: 'GET',
            dataType: 'json',
            data:{
                id_asset: value,
            },
            success: function(response) {
                if (response.success) {
                    const select = $('#lokasiAssetCreateServicePerencanaan');
                    select.empty();
                    response.data.forEach((item) => {
                        select.append(
                            `<option value="${item.id}" selected>${item.text}</option>`);
                    });
                }
            }
        })
    }

    const generateOptionLokasi = () => {
        $.ajax({
            url: '{{ route('admin.setting.lokasi.get-select2') }}',
            type: 'GET',
            success: function (response) {
                if (response.success) {
                    let data = response.data;
                    let option = '';
                    data.forEach(element => {
                        option += `<option value="${element.id}">${element.text}</option>`;
                    });
                    $('#lokasiAssetCreateService').append(option);
                }
            }
        })
    }

    $(document).ready(function() {
        generateOptionLokasi();
    })

    const generateAssetSelect2Create = (idElement, idLokasi) => {
        $('#' + idElement).removeAttr('disabled');
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Asset',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.listing-asset.get-all-data-asset-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_lokasi: idLokasi,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }

    const generateAssetServiceDateSelect2Create = (idElement, idAsset) => {
        $('#' + idElement).removeAttr('disabled');
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Tanggal Service',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.services.get-data-perencanaan-service') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }

    const generateSelect2KategoriService = (id) => {
        $('#' + id).select2({
            width: '100%',
            placeholder: 'Pilih Kategori Service',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kategori-service.get-data-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }
</script>
