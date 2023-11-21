<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSsoDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Ardhi Priagung';
        $user->email = 'ardhi.pria@gmail.com';
        $user->password = bcrypt('password');
        $user->role = 'admin';
        $user->username_sso = 'apriagung';
        $user->is_active = '1';
        $user->save();
    }
}
