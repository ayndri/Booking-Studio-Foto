<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use App\Models\Studio;
use Illuminate\Database\Seeder;

class ServicePackageSeeder extends Seeder
{
    /**
     * Seed paket layanan sesuai data produksi terkini.
     */
    public function run(): void
    {
        // Map studio berdasarkan slug agar tidak bergantung pada urutan id.
        $studioIds = Studio::query()->pluck('id', 'slug');

        $packages = [
            ['studio' => 'studio-foto-grup', 'name' => 'Family', 'description' => 'Sesi foto keluarga dengan properti lengkap dan konsep yang hangat.', 'price' => 300000, 'duration_minutes' => 60],
            ['studio' => 'studio-foto-grup', 'name' => 'Group', 'description' => 'Sesi foto grup hingga 15 orang, cocok untuk komunitas dan corporate.', 'price' => 450000, 'duration_minutes' => 90],
            ['studio' => 'studio-foto-produk', 'name' => 'Headshot', 'description' => 'Sesi portrait profesional untuk kebutuhan LinkedIn, ID, dan branding personal.', 'price' => 150000, 'duration_minutes' => 30],
            ['studio' => 'studio-foto-produk', 'name' => 'Couple', 'description' => 'Sesi foto berdua dengan berbagai konsep romantis dan modern.', 'price' => 350000, 'duration_minutes' => 60],
            ['studio' => 'studio-foto-grup', 'name' => 'Maternity', 'description' => 'Sesi foto kehamilan yang cantik dan berkesan untuk calon ibu.', 'price' => 400000, 'duration_minutes' => 60],
            ['studio' => 'studio-foto-produk', 'name' => 'Pre-Wedding', 'description' => 'Sesi foto pra-nikah dengan konsep elegan dan penuh cerita.', 'price' => 600000, 'duration_minutes' => 120],
            ['studio' => 'pas-foto', 'name' => 'Pas Foto', 'description' => 'Pas foto formal untuk keperluan dokumen, CV, dan ijazah.', 'price' => 50000, 'duration_minutes' => 15],
            ['studio' => 'studio-foto-grup', 'name' => 'Garden', 'description' => 'Sesi foto outdoor konsep taman dengan properti natural dan fresh.', 'price' => 400000, 'duration_minutes' => 60],
        ];

        foreach ($packages as $package) {
            $studioId = $studioIds->get($package['studio']);

            if (!$studioId) {
                continue;
            }

            ServicePackage::updateOrCreate(
                [
                    'studio_id' => $studioId,
                    'name' => $package['name'],
                ],
                [
                    'studio_id' => $studioId,
                    'name' => $package['name'],
                    'description' => $package['description'],
                    'price' => $package['price'],
                    'duration_minutes' => $package['duration_minutes'],
                    'is_active' => true,
                ]
            );
        }
    }
}
