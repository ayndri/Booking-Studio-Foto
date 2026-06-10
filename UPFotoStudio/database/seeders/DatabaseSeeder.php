<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed seluruh data contoh aplikasi.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StudioSeeder::class,
            ServicePackageSeeder::class,
            WebsiteContentSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
