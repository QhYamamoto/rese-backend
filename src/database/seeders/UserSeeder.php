<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* 管理者のダミーデータ */
        User::create([
            'group' => 100,
            'name' => 'テストユーザー(管理者)',
            'email' => 'admin@ex.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'),
        ]);

        /* 店舗代表者のダミーデータ */
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'group' => 10,
                'name' => 'テストユーザー(店舗代表者'.$i.')',
                'email' => 'representative'.$i.'@ex.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
            ]);
        }
        
        /* 一般ユーザーのダミーデータ */
        User::create([
            'name' => 'テストユーザー(一般)',
            'email' => 'test@ex.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'),
        ]);
    }
}
