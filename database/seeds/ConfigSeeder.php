<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configs = [
            [
                'key' => 'store_name',
                'value' => 'POS TOKO AISYAH',
            ],
            [
                'key' => 'store_description',
                'value' => 'Simpur Center Lantai 2 Blok C no:009/010',
            ],
            [
                'key' => 'prefix_barcode',
                'value' => 'BRG',
            ],
            [
                'key' => 'prefix_supplier',
                'value' => 'SPL',
            ],
            [
                'key' => 'prefix_kode_pembelian',
                'value' => 'PB/TA/',
            ],
            [
                'key' => 'prefix_kode_penjualan',
                'value' => 'PJ/TA/',
            ],
            [
                'key' => 'prefix_kode_kas',
                'value' => 'KAS/TA/',
            ],
        ];

        foreach ($configs as $config) {
            $exists = DB::table('config')->where('key', $config['key'])->first();
            if (!$exists) {
                DB::table('config')->insert(array_merge($config, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
