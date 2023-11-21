<?php

namespace App\Services\User;

use App\Models\User;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\UserChangePasswordRequest;

class UserCommandServices
{
    public function store(UserStoreRequest $request)
    {
        $request->validated();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username_sso = $request->username_sso;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->is_active = isset($request->status) ? $request->status : '0';
        $user->unit_kerja = $request->unit_kerja;
        $user->jabatan = $request->jabatan;
        $user->save();

        return $user;
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $request->validated();

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username_sso = $request->username_sso;
        $user->role = $request->role;
        $user->is_active = isset($request->status) ? $request->status : '0';
        $user->unit_kerja = $request->unit_kerja;
        $user->jabatan = $request->jabatan;
        $user->save();

        return $user;
    }

    public function delete(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return $user;
    }

    public function changePassword(UserChangePasswordRequest $request, $id)
    {
        $user = User::find($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }
}
