@extends('layouts.admin.main.master')
@section('plugin_css')
     <link href="{{ asset('assets/vendors/general/summernote/dist/summernote.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/summernote/dist/summernote.js') }}" type="text/javascript"></script>
@endsection
@section('custom_js')
    <script>
        $(document).ready(function() {
            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    $(formElement).find(".invalid-feedback").remove();
                    $(formElement).find(".is-invalid").removeClass("is-invalid");
                    showToastSuccess('Sukses', data.message);
                }
            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                for (let key in errors) {
                    let key_split = key.split('.');
                    let name = `config[${key_split[1]}]`;
                    console.log(name);
                    let element = formElement.find(`[name='${name}']`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                }
            });

            summerNotesInit();
        });

        const summerNotesInit = () => {
            $('.summernote').summernote({
                height: 150
            });
        }
    </script>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-2 col-12">
            @include('pages.admin.settings.menu')
        </div>
        <div class="col-md-10 col-12">
            <div class="kt-portlet shadow-custom">
                <div class="kt-portlet__head px-4">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Config Sistem
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form action="{{ route('admin.sistem-config.update') }}" class="form-submit" method="POST">
                        @csrf
                        <div class="row">
                            @foreach ($sistemConfigs as $item)
                                @if ($item->config == 'tentang_aplikasi')
                                    <div class="form-group col-md-12 col-12">
                                        <label for="">{{ $item->config_name }}</label>
                                        <textarea name="config[{{ $item->config }}]" id="" cols="30" rows="10"
                                            class="form-control summernote">{{ $item->value }}</textarea>
                                    </div>
                                @else
                                    <div class="form-group col-md-12 col-12">
                                        <label for="">{{ $item->config_name }}</label>
                                        <input type="text" class="form-control" value="{{ $item->value }}"
                                            name="config[{{ $item->config }}]">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
