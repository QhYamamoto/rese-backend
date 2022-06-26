<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* 一般ユーザーを取得 */
        $normalUser = User::where('group', 1)->first();

        $rand = rand(1, 4);
        for ($i = 0; $i < 5; $i++) {
            Favorite::create([
                'user_id' => $normalUser->id,
                'shop_id' => ($i === 0) ? $rand :  $rand += rand(1, 4),
            ]);
        }
    }
}
