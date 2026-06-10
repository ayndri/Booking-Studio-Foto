<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use App\Models\Studio;
use Illuminate\Database\Seeder;

class ServicePackageSeeder extends Seeder
{
    /**
     * Seed paket layanan dengan harga & durasi fleksibel.
     */
    public function run(): void
    {
        $aurora = Studio::where('slug', 'studio-aurora')->first();
        $mono = Studio::where('slug', 'studio-monochrome')->first();
        $kids = Studio::where('slug', 'studio-kids')->first();

        $packages = [
            [
                'studio_id' => $aurora?->id,
                'name' => 'Basic Portrait 30 Menit',
                'description' => 'Sesi portrait singkat 30 menit.',
                'price' => 150000,
                'duration_minutes' => 30,
                'is_active' => true,
            ],
            [
                'studio_id' => $aurora?->id,
                'name' => 'Premium Portrait 60 Menit',
                'description' => 'Sesi portrait lengkap dengan 2 set background.',
                'price' => 300000,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'studio_id' => $mono?->id,
                'name' => 'Editorial Session 90 Menit',
                'description' => 'Sesi editorial untuk personal branding.',
                'price' => 450000,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
            [
                'studio_id' => $kids?->id,
                'name' => 'Family Kids 60 Menit',
                'description' => 'Sesi keluarga dengan properti anak.',
                'price' => 350000,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            if (!$package['studio_id']) {
                continue;
            }

            ServicePackage::updateOrCreate(
                [
                    'studio_id' => $package['studio_id'],
                    'name' => $package['name'],
                ],
                $package
            );
        }
    }
}
