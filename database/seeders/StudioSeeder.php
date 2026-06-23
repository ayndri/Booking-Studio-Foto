<?php

namespace Database\Seeders;

use App\Models\Studio;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    /**
     * Seed data studio sesuai data produksi terkini.
     */
    public function run(): void
    {
        $studios = [
            [
                'name' => 'Studio Foto Grup',
                'slug' => 'studio-foto-grup',
                'location' => 'Lantai 2, UPFotoStudio',
                'description' => 'Ruangan luas untuk sesi foto grup, keluarga, couple, maternity, dan pre-wedding.',
                'is_active' => true,
            ],
            [
                'name' => 'Studio Foto Produk',
                'slug' => 'studio-foto-produk',
                'location' => 'Lantai 3, UPFotoStudio',
                'description' => 'Ruangan profesional untuk headshot, garden look, dan branding produk.',
                'is_active' => true,
            ],
            [
                'name' => 'Pas Foto',
                'slug' => 'pas-foto',
                'location' => 'Lantai 2, UPFotoStudio (Ruangan terpisah)',
                'description' => 'Ruangan khusus untuk kebutuhan pas foto dan identity photo formal.',
                'is_active' => true,
            ],
        ];

        foreach ($studios as $studio) {
            Studio::updateOrCreate(['slug' => $studio['slug']], $studio);
        }
    }
}
