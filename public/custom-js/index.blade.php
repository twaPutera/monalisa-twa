@extends('layouts.admin.main.master')

@section('plugin_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.2/dist/select2-bootstrap4.min.css">
<link href="{{ asset('assets/css/tree/style.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
<link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('plugin_js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/vendors/custom/vendors/js-tree/jstree.min.js') }}" type="text/javascript"></script>
@endsection

@section('custom_js')
<script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}" type="text/javascript"></script>
<script src="{{asset('custom-js/btn_action.js')}}"></script>
<script src="{{ asset('custom-js/form_send.js')}}"></script>
<script>
    let dt_table = $('.dt_table').DataTable({
        destroy : true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        fixedHeader: true,
        // searching: false,
        language: {
            "lengthMenu": "Tampilkan _MENU_",
            "zeroRecords": "Tidak ada yang ditemukan - maaf",
            "info": "Menampilkan _START_ - _END_ dari _TOTAL_ halaman",

            "search": "Cari:",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari total _MAX_ data)"
        },
        dom: `<'row'<'col-sm-12'tr>> <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        ajax: {
            url : "{{ $route_dt_table }}"
        },
        columns: [
        { data: "DT_RowIndex", class:"text-center", orderable: false, searchable: false, name: 'DT_RowIndex' },
        { data: 'link', class:"text-center", orderable: false, searchable: false, render: function ( data, type, row ) { return data; } },
        { data: "name", class:"wrap" },
        { data: "ruang_penyimpanan", class:"wrap" },
        ],
        drawCallback: function( settings ) {
        }
    });
    $('#my_search').keyup(function(){
      dt_table.search($(this).val()).draw() ;
  })
</script>
@include('pages.admin.struktur-arsip.tree')
@endsection

@section('subheader')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Beranda
        </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        {!! $breadcrumb !!}
    </div>
    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
        </div>
    </div>
</div>
@endsection

@section('main-content')
<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-lg-3">
            <h4 class="my-color-blue2 text-uppercase font-weight-bold mb-4">Struktur Arsip</h4>
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <div id="data" class="demo"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <h4 class="my-color-blue2 text-uppercase font-weight-bold mb-4">
                {{ $title }}
            </h4>
            @include('pages.admin.struktur-arsip.tab')
            <div class="kt-portlet" style="border-top-right-radius: 0; border-top-left-radius: 0;">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <div class="btn-group btn-open-modal" data-modal-name="modal-tambah">
                            <button type="button" class="btn btn-sm btn-icon btn-success mr-1" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18.911" height="18.911" viewBox="0 0 18.911 18.911">
                                  <path id="Icon_awesome-plus-circle" data-name="Icon awesome-plus-circle" d="M10.018.563a9.455,9.455,0,1,0,9.455,9.455A9.454,9.454,0,0,0,10.018.563Zm5.49,10.523a.459.459,0,0,1-.458.458H11.543V15.05a.459.459,0,0,1-.458.458H8.95a.459.459,0,0,1-.458-.458V11.543H4.985a.459.459,0,0,1-.458-.458V8.95a.459.459,0,0,1,.458-.458H8.493V4.985a.459.459,0,0,1,.458-.458h2.135a.459.459,0,0,1,.458.458V8.493H15.05a.459.459,0,0,1,.458.458Z" transform="translate(-0.563 -0.563)" fill="#fff"/>
                              </svg>
                          </button>
                          <button type="button" class="btn btn-sm btn-success">Tambah Data </button>
                      </div>
                  </div>
                  <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <div class="btn-group btn-reload-dt-table">
                                <button type="button" class="btn btn-sm btn-purple btn-icon mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19.938" height="19.938" viewBox="0 0 19.938 19.938">
                                        <path id="Icon_ionic-md-refresh-circle" data-name="Icon ionic-md-refresh-circle" d="M13.344,3.375a9.969,9.969,0,1,0,9.969,9.969A10,10,0,0,0,13.344,3.375Zm5.368,9.3h-4.7l2.147-2.147a3.9,3.9,0,0,0-2.818-1.208,4.026,4.026,0,0,0,0,8.052,4,4,0,0,0,3.719-2.492h1.428a5.381,5.381,0,1,1-1.356-5.325l1.577-1.577Z" transform="translate(-3.375 -3.375)" fill="#fff"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body pt-3">
                <div class="row filter-dt-table">
                    <div class="col-md-4"> </div>
                    <div class="col-md-4"> </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <input class="form-control" type="text" id="my_search" placeholder="Cari Data">
                                <div class="input-group-append search">
                                    <span class="input-group-text">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered dt_table">
                        <thead>
                            <tr>
                                <td class="text-center" width="50">No</td>
                                <td class="text-center" width="70">Aksi</td>
                                <td>Nama Lemari</td>
                                <td>Ruang Penyimpanan</td>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-plus"></i> {{ $title }}
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ $route_insert }}" class="form-horizontal form-send" role="form" method="post" autocomplete="off">
                <div class="modal-body form">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group">
                            <label>Ruang Penyimpanan</label>
                            <select class="form-control select2" name="ruang_penyimpanan_id">
                                @foreach ($ruangPenyimpananList as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Lemari</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-edit"></i> {{ $title }}
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" class="form-horizontal form-send" role="form" method="post" autocomplete="off">
                <div class="modal-body form">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group">
                            <label>Ruang Penyimpanan</label>
                            <select class="form-control select2" name="ruang_penyimpanan_id">
                                @foreach ($ruangPenyimpananList as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Lemari</label>
                            <input id="name" type="text" name="name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
