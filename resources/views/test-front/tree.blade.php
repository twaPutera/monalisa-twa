@extends('layouts.admin.main.master')
@section('custom_css')
    <link href="{{ asset('assets/vendors/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
    <script>
        var demo2 = function () {
            $("#kt_tree_2").jstree({
                "core" : {
                    "themes" : {
                        "responsive": false
                    },
                    // so that create works
                    "check_callback" : true,
                    'data': [
                        {
                            "text": "Lokasi",
                            "icon": "fa fa-map-marker kt-font-primary",
                            "children": [
                                {
                                    "text": "Lantai",
                                    "icon": "fa fa-home",
                                    "state": {
                                        "selected": true
                                    },
                                    "children": [
                                        {
                                            "text": "Ruangan A",
                                            "icon": "fas fa-door-open",
                                        },
                                        {
                                            "text": "Ruangan B",
                                            "icon": "fas fa-door-open",
                                        }
                                    ]
                                }
                            ]
                        },
                        "Another Node"
                    ]
                },
                "types" : {
                    "default" : {
                        "icon" : "fa fa-folder kt-font-success"
                    },
                    "file" : {
                        "icon" : "fa fa-file  kt-font-success"
                    }
                },
                "state" : { "key" : "demo2" },
                "plugins" : [ "dnd", "state", "types" ]
            });
        }

        $(document).ready(function() {
            demo2();
        });
    </script>
@endsection
@section('main-content')
<div class="row">
    <div class="col-md-3">
        <div id="kt_tree_2" class="tree-demo">

        </div>
    </div>
</div>
@endsection