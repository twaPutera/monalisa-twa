<script>
    const generateGroupSelect2Edit = () => {
        $('#groupAssetEdit').select2({
            width: '100%',
            placeholder: 'Pilih Kelompok',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.group-kategori-asset.get-data-select2') }}',
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
        }).on('change', function() {
            generateKategoriSelect2Edit();
        });
    }

    const generateKategoriSelect2Edit = () => {
        $('#kategoriAssetEdit').removeAttr('disabled');
        $('#kategoriAssetEdit').select2({
            width: '100%',
            placeholder: 'Pilih Jenis',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_group_kategori_asset: $('#groupAssetEdit').val(),
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    const results = data.data.map(item => ({
                        id: item.id,
                        text: item.text + ' (' + item.dataKodeKategori + ')',
                    }));
                    return {
                        results: results,
                    };
                },
                cache: true
            },
        });
    }

    $('.modalEditDraftAsset').on('shown.bs.modal', function() {
        setTimeout(() => {
            generateGroupSelect2Edit();
            generateSelect2LokasiEdit();
            generateKelasAssetEdit();
            generateSatuanAssetEdit();
            generateVendorAssetEdit();
            generateOwnerAssetEdit();
            generateMemorandumAndinSelect2(false);
            generateKategoriSelect2Edit();
            select2StatusKondisiEdit();
            select2StatusAkuntingEdit();
        }, 1000);
    });

    $('#groupAssetCreate').on('change', function() {
        generateKategoriSelect2Create('kategoriAssetCreate', $(this).val());
    });

    $('#groupAssetSearch').on('change', function() {
        generateKategoriSelect2Create('kategoriAssetSearch', $(this).val());
    });

    $('#lokasiAssetCreateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocation', $(this).val());
    });

    const generateSelect2LokasiEdit = (id) => {
        $('#lokasiAssetEdit').select2({
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

    // const generateAssetSelect2Create = (idElement, idLokasi) => {
    //     $('#' + idElement).removeAttr('disabled');
    //     $('#' + idElement).select2({
    //         width: '100%',
    //         placeholder: 'Pilih Jenis',
    //         dropdownParent: $('.modal.show'),
    //         ajax: {
    //             url: '{{ route('admin.listing-asset.get-all-data-asset-select2') }}',
    //             dataType: 'json',
    //             delay: 250,
    //             data: function(params) {
    //                 return {
    //                     keyword: params.term, // search term
    //                     id_lokasi: idLokasi,
    //                 };
    //             },
    //             processResults: function(data, params) {
    //                 params.page = params.page || 1;
    //                 return {
    //                     results: data.data,
    //                 };
    //             },
    //             cache: true
    //         },
    //     });
    // }

    const generateKelasAssetEdit = () => {
        $('#kelasAssetEdit').select2({
            width: '100%',
            placeholder: 'Pilih Kelas',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kelas-asset.get-data-select2') }}',
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

    const generateVendorAssetEdit = () => {
        $('#vendorAssetEdit').select2({
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

    const generateSatuanAssetEdit = () => {
        $('#satuanAssetEdit').select2({
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

    const generateOwnerAssetEdit = () => {
        $('#ownershipAssetEdit').select2({
            width: '100%',
            placeholder: 'Pilih Pemegang',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.listing-asset.get-all-data-owner-select2') }}',
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

    $("#kategoriAssetSearch").on('change', function() {
        generateSelect2AssetDataSearchEdit();
    });

    const generateSelect2AssetDataSearchEdit = () => {
        $('#assetDataSearch').select2({
            width: '100%',
            placeholder: 'Pilih Asset',
            dropdownParent: $('#modalSearchAsset'),
            ajax: {
                url: '{{ route('admin.listing-asset.get-all-data-asset-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_kategori_asset: $('#kategoriAssetSearch').val(),
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

    $("#buttonSearchAsset").on('click', function() {
        $('#asal_asset_preview').val($("#assetDataSearch").children("option:selected").text());
        $('#asal_asset_id').val($("#assetDataSearch").val());
        $('#modalSearchAsset').modal('hide');
    });

    // const jenisAssetChange = (select) => {
    //     const assetLama = $(select).children("option:selected").data('asset-lama');
    //     if (assetLama == "1") {
    //         $('#asal-asset-container').show();
    //         $("#modalSearchAsset").on('shown.bs.modal', function() {
    //             generateGroupSelect2('groupAssetSearch');
    //         }).modal('show');
    //     } else {
    //         $('#asal-asset-container').hide();
    //         $('#asal_asset_preview').val("");
    //         $('#asal_asset_id').val("");
    //     }
    // }

    const select2StatusAkuntingEdit = () => {
        $('#status_akunting_edit').select2({
            width: '60%',
            placeholder: 'Pilih Status Akunting',
            dropdownParent: $('.modal.show'),
        });
    }

    const select2StatusKondisiEdit = () => {
        $('#status_kondisi_edit').select2({
            width: '60%',
            placeholder: 'Pilih Status Kondisi',
            dropdownParent: $('.modal.show'),
        });
    }
</script>
