<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(ReservationSeeder::class);
        $this->call(FavoriteSeeder::class);
    }
}
