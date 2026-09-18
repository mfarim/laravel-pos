<?php

use Illuminate\Database\Seeder;
use App\BarangSatuan;

class BarangSatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            'PCS',
            'SACHET',
            'DUS',
            'PACK',
            'BOTOL',
        ];

        foreach ($units as $unit) {
            $exists = BarangSatuan::where('unit', $unit)->first();
            if (!$exists) {
                BarangSatuan::create([
                    'unit' => $unit
                ]);
            }
        }
    }
}
