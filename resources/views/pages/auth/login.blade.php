@extends('layouts.auth.master')
@section('title', 'Login')
@section('content')
<div class="w-75">
    <h4 class="mb-4 text-center text-primary"><strong>Login</strong></h4>
    <form action="{{ route('login.action') }}" method="POST" class="form-sso">
        @csrf
        <div class="form-group">
            <div class="form-cointainer @error('email') is-invalid @enderror">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20">
                    <path id="Path_74" data-name="Path 74" d="M16,7a4,4,0,1,1-4-4A4,4,0,0,1,16,7Zm-4,7a7,7,0,0,0-7,7H19a7,7,0,0,0-7-7Z" transform="translate(-4 -2)" fill="none" stroke="#388edb" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                <input type="text" name="email" class="ml-3" value="{{ old('email') }}" placeholder="Your Email">
                @error('email')
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#eb1e13" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8.998 3a1 1 0 112 0 1 1 0 01-2 0zM10 6a.905.905 0 00-.9.995l.35 3.507a.553.553 0 001.1 0l.35-3.507A.905.905 0 0010 6z" clip-rule="evenodd"/>
                    </svg>
                @enderror
            </div>
            @error('email')
                <small class="text-danger" style="font-size: 14px;"><strong>{{ $message }}</strong></small>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-cointainer @error('password') is-invalid @enderror">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20.087" viewBox="0 0 20 20.087">
                    <path id="Path_75" data-name="Path 75" d="M15,7a2,2,0,0,1,2,2m4,0a6,6,0,0,1-7.743,5.743L11,17H9v2H7v2H4a1,1,0,0,1-1-1V17.414a1,1,0,0,1,.293-.707l5.964-5.964A6,6,0,1,1,21,9Z" transform="translate(-2 -1.913)" fill="none" stroke="#388edb" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                <input type="password" name="password" class="mx-3" placeholder="Your Password">
                @error('password')
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#eb1e13" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8.998 3a1 1 0 112 0 1 1 0 01-2 0zM10 6a.905.905 0 00-.9.995l.35 3.507a.553.553 0 001.1 0l.35-3.507A.905.905 0 0010 6z" clip-rule="evenodd"/>
                    </svg>
                @enderror
            </div>
            @error('password')
                <small class="text-danger" style="font-size: 14px;"><strong>{{ $message }}</strong></small>
            @enderror
        </div>
        <div class="mt-2 d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary"><strong>Log Inn</strong></button>
        </div>
    </form>
</div>
@endsection
