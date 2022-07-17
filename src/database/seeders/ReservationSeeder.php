<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;

class ReservationSeeder extends Seeder
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

        /* 10日前の予約(来店済み)を5件登録 */
        for ($i = 1; $i <= 5; $i++) {
            $date = date("Y-m-d", strtotime("-".$i."days"));
            Reservation::create([
                'user_id' => $normalUser->id,
                'shop_id' => $i,
                /* ラーメン屋(shop_id=5)以外はコースを事前注文したことにする */
                'course_id' => ($i !== 5) ? rand(1, 3) * $i : null,
                'datetime' => $date.' 19:00:00',
                'number' => 2,
                'visit_completed' => true,
            ]);
        }
        
        /* 10日後の予約を5件登録 */
        for ($i = 1; $i <= 5; $i++) {
            $date = date("Y-m-d", strtotime("+".$i."days"));
            Reservation::create([
                'user_id' => $normalUser->id,
                'shop_id' => $i,
                /* ラーメン屋(shop_id=5)以外はコースを事前注文したことにする(決済はまだ) */
                'course_id' => ($i !== 5) ? rand(1, 3) + (($i - 1) * 3) : null,
                'datetime' => $date.' 19:00:00',
                'number' => 2,
            ]);
        }
    }
}
