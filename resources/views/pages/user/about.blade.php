@extends('layouts.user.master')
@section('page-title', 'Tentang Aplikasi')
@section('custom-js')

@endsection
@section('content')
    <div class="section mt-2">
        <h1>
            {{ $tentang_aplikasi->config_name }}
        </h1>
    </div>

    <div class="section mt-2">
        {!! $tentang_aplikasi->value !!}
    </div>
@endsection
