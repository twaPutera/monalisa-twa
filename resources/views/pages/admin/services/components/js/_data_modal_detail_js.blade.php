<script>
    $(document).ready(function() {
        var table2 = $('#datatableLogService2');
        table2.DataTable({
            responsive: true,
            processing: true,
            searching: false,
            ordering: false,
            serverSide: true,
            bLengthChange: false,
            autoWidth: false,
            paging: false,
            info: false,
            ajax: {
                url: "{{ route('admin.services.datatable.log') }}",
                data: function(d) {
                    d.id_service = "{{ $listing_asset_service->id }}"
                }
            },
            columns: [{
                    name: 'tanggal',
                    data: 'tanggal',
                },
                {
                    name: 'message_log',
                    data: 'message_log'
                },
                {
                    data: 'status'
                },
                {
                    name: 'created_by',
                    data: 'created_by'
                },

            ],
            columnDefs: [
                //Custom template data
            ],
        });
    });
</script>
