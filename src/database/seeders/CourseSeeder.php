<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Shop;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shops = Shop::with('genre')->get();

        foreach ($shops as $shop) {
            if ($shop->genre->name === 'ラーメン') {
                continue;
            }

            for ($i = 1; $i <= 3; $i++) {
                $rand = rand(10, 50);
                Course::create([
                    'shop_id' => $shop->id,
                    'name' => 'コース'.$i,
                    'price' => $rand * 200,
                    'description' => 'コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。コースの説明が入ります。',
                ]);
            }
        }
    }
}
