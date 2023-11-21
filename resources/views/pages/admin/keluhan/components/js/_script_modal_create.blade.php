<script>
    $('.modalFilterAsset').on('shown.bs.modal', function() {
        setTimeout(() => {
            // generateSelect2Lokasi('lokasiAssetCreateService');
            $('.lokasiSelect').select2({
                width: '100%',
                placeholder: 'Pilih Lokasi',
                dropdownParent: $('.modal.show'),
            });
            generateAssetSelect2Create('listAssetLocation', 'root');
        }, 2000);
    });

    $('#lokasiAssetCreateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocation', $(this).val());
    });

    $('#listAssetLocation').on('change', function() {
        // generateSelect2Lokasi('lokasiAssetCreateService');
    });

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
                    $('.lokasiSelect').append(option);
                }
            }
        })
    }

    $(document).ready(function() {
        generateOptionLokasi();
    })

    const generateSelect2Lokasi = (id) => {
        $('#' + id).select2({
            width: '100%',
            placeholder: 'Pilih Lokasi',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.lokasi.get-select2') }}',
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

</script>
