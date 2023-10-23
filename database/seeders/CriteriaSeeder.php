<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Criteria::insert([
            [
                'criteria' => 'jarak dengan pusat niaga terdekat(km)',
                'weight' => rand(1, 5),
            ],
            [
                'criteria' => 'kepadatan penduduk disekitar lokasi (orang/km2)',
                'weight' => rand(1, 5),
            ],
            [
                'criteria' => 'jarak dari pabrik(km)',
                'weight' => rand(1, 5),
            ],
            [
                'criteria' => 'jarak dengan gudang yang sudah ada (km)',
                'weight' => rand(1, 5),
            ],
            [
                'criteria' => 'harga tanah untuk lokasi (x1000 Rp/m2)',
                'weight' => rand(1, 5),
            ],
        ]);
    }
}
