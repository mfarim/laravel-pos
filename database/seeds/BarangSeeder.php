<?php

use Illuminate\Database\Seeder;
use App\Barang;
use App\BarangKategori;
use App\BarangSatuan;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategoriMie = BarangKategori::where('kategori', 'MIE INSTANT')->first();
        $kategoriMinuman = BarangKategori::where('kategori', 'MINUMAN')->first();
        $kategoriKopi = BarangKategori::where('kategori', 'KOPI')->first();
        $kategoriSnack = BarangKategori::where('kategori', 'SNACK / MAKANAN RINGAN')->first();
        $kategoriBayi = BarangKategori::where('kategori', 'PERLENGKAPAN BAYI & ANAK')->first();

        $satuanPcs = BarangSatuan::where('unit', 'PCS')->first();
        $satuanSachet = BarangSatuan::where('unit', 'SACHET')->first();
        $satuanBotol = BarangSatuan::where('unit', 'BOTOL')->first();
        $satuanPack = BarangSatuan::where('unit', 'PACK')->first();

        $barangs = [
            [
                'kode_barang' => 'BRG-0001',
                'nama_barang' => 'Mie Sedaap Rasa Kare',
                'harga_beli' => 1200,
                'harga_jual' => 1500,
                'idbarang_kategori' => $kategoriMie ? $kategoriMie->idbarang_kategori : 1,
                'idbarang_unit' => $satuanPcs ? $satuanPcs->idbarang_unit : 1,
                'stok' => 99,
                'diskon' => 0,
                'harga_jual_akhir' => 1500,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0002',
                'nama_barang' => 'Indomie Rasa Soto',
                'harga_beli' => 1200,
                'harga_jual' => 1500,
                'idbarang_kategori' => $kategoriMie ? $kategoriMie->idbarang_kategori : 1,
                'idbarang_unit' => $satuanPcs ? $satuanPcs->idbarang_unit : 1,
                'stok' => 98,
                'diskon' => 0,
                'harga_jual_akhir' => 1500,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0003',
                'nama_barang' => 'Mie Sedaap Rasa Ayam Bawang',
                'harga_beli' => 1200,
                'harga_jual' => 1500,
                'idbarang_kategori' => $kategoriMie ? $kategoriMie->idbarang_kategori : 1,
                'idbarang_unit' => $satuanPcs ? $satuanPcs->idbarang_unit : 1,
                'stok' => 50,
                'diskon' => 5,
                'harga_jual_akhir' => 1425,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0004',
                'nama_barang' => 'Aqua Botol 1500 ML',
                'harga_beli' => 4500,
                'harga_jual' => 5000,
                'idbarang_kategori' => $kategoriMinuman ? $kategoriMinuman->idbarang_kategori : 2,
                'idbarang_unit' => $satuanPcs ? $satuanPcs->idbarang_unit : 1,
                'stok' => 40,
                'diskon' => 5,
                'harga_jual_akhir' => 4750,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0005',
                'nama_barang' => 'Kapal Api Special Mix',
                'harga_beli' => 800,
                'harga_jual' => 1000,
                'idbarang_kategori' => $kategoriKopi ? $kategoriKopi->idbarang_kategori : 3,
                'idbarang_unit' => $satuanSachet ? $satuanSachet->idbarang_unit : 2,
                'stok' => 100,
                'diskon' => 0,
                'harga_jual_akhir' => 1000,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0006',
                'nama_barang' => 'Baju Bayi Setelan Katun',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'idbarang_kategori' => $kategoriBayi ? $kategoriBayi->idbarang_kategori : 5,
                'idbarang_unit' => $satuanPcs ? $satuanPcs->idbarang_unit : 1,
                'stok' => 25,
                'diskon' => 0,
                'harga_jual_akhir' => 35000,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0007',
                'nama_barang' => 'Teh Botol Sosro 450ml',
                'harga_beli' => 3500,
                'harga_jual' => 4500,
                'idbarang_kategori' => $kategoriMinuman ? $kategoriMinuman->idbarang_kategori : 2,
                'idbarang_unit' => $satuanBotol ? $satuanBotol->idbarang_unit : 5,
                'stok' => 35,
                'diskon' => 0,
                'harga_jual_akhir' => 4500,
                'createdby' => 'Administrator',
            ],
            [
                'kode_barang' => 'BRG-0008',
                'nama_barang' => 'Chitato Sapi Panggang 68g',
                'harga_beli' => 9000,
                'harga_jual' => 11500,
                'idbarang_kategori' => $kategoriSnack ? $kategoriSnack->idbarang_kategori : 4,
                'idbarang_unit' => $satuanPack ? $satuanPack->idbarang_unit : 4,
                'stok' => 30,
                'diskon' => 0,
                'harga_jual_akhir' => 11500,
                'createdby' => 'Administrator',
            ],
        ];

        foreach ($barangs as $data) {
            $exists = Barang::where('kode_barang', $data['kode_barang'])->first();
            if (!$exists) {
                Barang::create($data);
            }
        }
    }
}
