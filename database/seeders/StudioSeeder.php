<?php

namespace Database\Seeders;

use App\Models\Studio;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    /**
     * Seed data studio awal.
     */
    public function run(): void
    {
        $studios = [
            [
                'name' => 'Studio Aurora',
                'slug' => 'studio-aurora',
                'location' => 'Lantai 1, UPFotoStudio',
                'description' => 'Ruangan bernuansa bright untuk portrait dan keluarga.',
                'is_active' => true,
            ],
            [
                'name' => 'Studio Monochrome',
                'slug' => 'studio-monochrome',
                'location' => 'Lantai 2, UPFotoStudio',
                'description' => 'Ruangan konsep editorial dengan lighting fleksibel.',
                'is_active' => true,
            ],
            [
                'name' => 'Studio Kids',
                'slug' => 'studio-kids',
                'location' => 'Lantai 1, UPFotoStudio',
                'description' => 'Ruang tematik untuk kebutuhan foto anak dan keluarga.',
                'is_active' => true,
            ],
        ];

        foreach ($studios as $studio) {
            Studio::updateOrCreate(['slug' => $studio['slug']], $studio);
        }
    }
}
