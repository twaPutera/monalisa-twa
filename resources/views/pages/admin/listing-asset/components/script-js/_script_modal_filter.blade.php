<script>
    const generateSatuanAssetFilter = () => {
        $('#satuanAssetFilter').select2({
            width: '100%',
            placeholder: 'Pilih Satuan',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.satuan-asset.get-data-select2') }}',
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

    const generateVendorAssetFilter = () => {
        $('#vendorAssetFilter').select2({
            width: '100%',
            placeholder: 'Pilih Vendor',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.vendor.get-data-select2') }}',
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

    const generateKategoriSelect2Filter = () => {
        $('#kategoriAssetFilter').select2({
            width: '100%',
            placeholder: 'Pilih Kategori',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        // id_group_kategori_asset: idGroup,
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

    $('.modalFilterAsset').on('shown.bs.modal', function() {
        setTimeout(() => {
            generateKategoriSelect2Filter();
            generateSatuanAssetFilter();
            generateVendorAssetFilter();
        }, 2000);
    });
</script>