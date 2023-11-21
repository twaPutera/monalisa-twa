@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var table = $('#datatableExample');
            table.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: '{{ route("test-front.datatable") }}',
                columns: [
                    { data: "DT_RowIndex", class:"text-center", orderable: false, searchable: false, name: 'DT_RowIndex' },
                    { data: 'name' },
                ],
                columnDefs: [
                    //Custom template data
                ],
            });
        });
    </script>
@endsection
@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet shadow-custom">
            <div class="kt-portlet__head px-4">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Base Datatable
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatableExample">
                        <thead>
                            <tr>
                                <th width="100px">No</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection