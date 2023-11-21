@extends('layouts.user.master')
@section('page-title', 'Dashboard')
@section('custom-js')

@endsection
@section('content')
    <div class="section mt-3 text-center">
        <div class="avatar-section">
            <a href="#">
                <img src="https://ui-avatars.com/api/?name={{ $user->name ?? 'No Name' }}&background=5174ff&color=fff"
                    alt="avatar" class="imaged w100 rounded">
                {{-- <span class="button">
                <ion-icon name="camera-outline"></ion-icon>
            </span> --}}
            </a>
        </div>
    </div>

    <div class="listview-title mt-1">Theme</div>
    <ul class="listview image-listview text inset no-line">
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        Dark Mode
                    </div>
                    <div class="form-check form-switch  ms-2">
                        <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch">
                        <label class="form-check-label" for="darkmodeSwitch"></label>
                    </div>
                </div>
            </div>
        </li>
    </ul>

    <div class="listview-title mt-1">Profile</div>
    <ul class="listview image-listview text inset">
        <li>
            <div class="item">
                <div class="in">
                    <div>Nama</div>
                    <div>{{ $user->name }}</div>
                </div>
            </div>
        </li>
        <li>
            <div class="item">
                <div class="in">
                    <div>Email</div>
                    <div>{{ $user->email }}</div>
                </div>
            </div>
        </li>
        <li>
            <div class="item">
                <div class="in">
                    <div>Username SSO</div>
                    <div>{{ $user->username_sso }}</div>
                </div>
            </div>
        </li>
        <li>
            <div class="item">
                <div class="in">
                    <div>Role</div>
                    <div>{!! App\Helpers\CutText::cutUnderscore($user->role) !!}</div>
                </div>
            </div>
        </li>
    </ul>
@endsection
