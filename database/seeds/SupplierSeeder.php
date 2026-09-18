<?php

use Illuminate\Database\Seeder;
use App\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
                'kode_supplier' => 'SPL-0001',
                'nama_supplier' => 'PT Indofood Sukses Makmur',
                'alamat_supplier' => 'Jl. Jend. Sudirman Kav. 76-78 Jakarta',
                'no_hp' => '081234567890',
                'keterangan' => 'Distributor Utama Mie Instant & Makanan Olahan',
            ],
            [
                'kode_supplier' => 'SPL-0002',
                'nama_supplier' => 'Wahyu Ramadhan',
                'alamat_supplier' => 'Jl. Raden Intan No. 12 Bandar Lampung',
                'no_hp' => '081398765432',
                'keterangan' => 'Grosir Minuman Ringan & Sembako',
            ],
            [
                'kode_supplier' => 'SPL-0003',
                'nama_supplier' => 'CV Sumber Rejeki',
                'alamat_supplier' => 'Jl. Kartini No. 88 Bandar Lampung',
                'no_hp' => '085212345678',
                'keterangan' => 'Supplier Perlengkapan Bayi & Pakaian Anak',
            ],
        ];

        foreach ($suppliers as $data) {
            $exists = Supplier::where('kode_supplier', $data['kode_supplier'])->first();
            if (!$exists) {
                Supplier::create($data);
            }
        }
    }
}
