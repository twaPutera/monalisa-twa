<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToModel, WithStartRow, WithValidation
{
    public function model(array $row)
    {
        $user = new User();
        $user->name = $row[0];
        $user->email = $row[1];
        $user->password = bcrypt($row[2]);
        $user->email_verified_at = now();
        $user->username_sso = $row[3];
        $user->role = $row[4];
        $user->is_active = '1';
        $user->no_induk = $row[5];
        $user->no_telp = $row[6];
        $user->jabatan = $row[7];
        $user->unit_kerja = $row[8];
        $user->save();
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required|email|unique:users,email',
            '2' => 'required',
            '3' => 'required|string|unique:users,username_sso',
            '4' => 'required|string|in:admin,manager,staff,user',
            '5' => 'nullable',
            '6' => 'nullable|max:50',
            '7' => 'nullable|max:255',
            '8' => 'nullable|max:255',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Nama',
            '1' => 'Email',
            '2' => 'Password',
            '3' => 'Username SSO',
            '4' => 'Role',
            '5' => 'No Induk',
            '6' => 'No Telp',
            '7' => 'Jabatan',
            '8' => 'Unit Kerja',
        ];
    }
}
