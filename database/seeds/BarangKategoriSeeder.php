<?php

use Illuminate\Database\Seeder;
use App\BarangKategori;

class BarangKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'MIE INSTANT',
            'MINUMAN',
            'KOPI',
            'SNACK / MAKANAN RINGAN',
            'PERLENGKAPAN BAYI & ANAK',
            'PAKAIAN',
        ];

        foreach ($categories as $kategori) {
            $exists = BarangKategori::where('kategori', $kategori)->first();
            if (!$exists) {
                BarangKategori::create([
                    'kategori' => $kategori
                ]);
            }
        }
    }
}
