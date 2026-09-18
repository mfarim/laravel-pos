<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(BarangKategoriSeeder::class);
        $this->call(BarangSatuanSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(BarangSeeder::class);
        $this->call(TransaksiSeeder::class);
    }
}
