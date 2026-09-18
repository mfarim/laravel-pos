<?php

use Illuminate\Database\Seeder;
use App\Barang;
use App\Supplier;
use App\Pembelian;
use App\DetailPembelian;
use App\Penjualan;
use App\DetailPenjualan;
use App\Kas;
use App\BarangPersediaan;
use App\KartuPersediaan;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplierIndofood = Supplier::where('kode_supplier', 'SPL-0001')->first();
        $supplierWahyu = Supplier::where('kode_supplier', 'SPL-0002')->first();

        $barang1 = Barang::where('kode_barang', 'BRG-0001')->first();
        $barang2 = Barang::where('kode_barang', 'BRG-0002')->first();
        $barang4 = Barang::where('kode_barang', 'BRG-0004')->first();

        if (!$supplierIndofood || !$barang1 || !$barang2) {
            return;
        }

        // 1. Sampel Transaksi Pembelian (Tahun 2018 & Saat Ini)
        $pembelianDates = [
            '2018-09-01',
            date('Y-m-d', strtotime('-5 days')),
        ];

        $pembelianCount = Pembelian::count();
        if ($pembelianCount == 0) {
            foreach ($pembelianDates as $index => $tgl) {
                $no = sprintf('%04d', $index + 1);
                $pembelian = Pembelian::create([
                    'kode_pembelian' => 'PB/TA/' . $no,
                    'id_supplier' => $supplierIndofood->id,
                    'tanggal' => $tgl,
                    'total_harga_beli' => 240000,
                    'total_bayar' => 240000,
                    'total_kembalian' => 0,
                    'tipe_pembayaran' => 'TUNAI',
                    'status' => 'Lunas',
                    'createdby' => 'Administrator',
                    'created_at' => $tgl . ' 08:30:00',
                    'updated_at' => $tgl . ' 08:30:00',
                ]);

                // Detail Pembelian
                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id,
                    'id_barang' => $barang1->id,
                    'tanggal' => $tgl,
                    'jumlah_beli' => 100,
                    'sub_total_harga' => 120000,
                    'status' => 'Lunas',
                    'createdby' => 'Administrator',
                    'created_at' => $tgl . ' 08:30:00',
                ]);

                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id,
                    'id_barang' => $barang2->id,
                    'tanggal' => $tgl,
                    'jumlah_beli' => 100,
                    'sub_total_harga' => 120000,
                    'status' => 'Lunas',
                    'createdby' => 'Administrator',
                    'created_at' => $tgl . ' 08:30:00',
                ]);

                // Kartu Persediaan Masuk
                KartuPersediaan::create([
                    'id_barang' => $barang1->id,
                    'keterangan' => 'Pembelian Barang',
                    'no_transaksi' => $pembelian->kode_pembelian,
                    'tanggal' => $tgl,
                    'masuk' => 100,
                    'keluar' => 0,
                    'sisa' => 100,
                    'createdby' => 'Administrator',
                    'created_at' => $tgl . ' 08:30:00',
                ]);

                // Catat Pengeluaran Kas (Kredit)
                $lastKas = Kas::orderBy('id', 'desc')->first();
                $saldoSebelumnya = $lastKas ? $lastKas->saldo : 1000000;
                Kas::create([
                    'kode_kas' => 'KAS/TA/' . sprintf('%04d', ($index * 2) + 1),
                    'id_pembelian' => $pembelian->id,
                    'tanggal' => $tgl,
                    'keterangan' => 'Pembayaran Pembelian ' . $pembelian->kode_pembelian,
                    'ref' => $pembelian->kode_pembelian,
                    'debit' => 0,
                    'kredit' => 240000,
                    'saldo' => max(0, $saldoSebelumnya - 240000),
                    'createdby' => 'Administrator',
                    'created_at' => $tgl . ' 08:30:00',
                ]);
            }
        }

        // 2. Sampel Transaksi Penjualan (Tahun 2018 & Saat Ini)
        $penjualanDates = [
            '2018-09-05',
            '2018-09-10',
            date('Y-m-d', strtotime('-2 days')),
            date('Y-m-d'),
        ];

        $penjualanCount = Penjualan::count();
        if ($penjualanCount == 0) {
            foreach ($penjualanDates as $idx => $tgl) {
                $no = sprintf('%04d', $idx + 1);
                $totalJual = 150000;
                $penjualan = Penjualan::create([
                    'kode_penjualan' => 'PJ/TA/' . $no,
                    'pelanggan' => 'Umum',
                    'tanggal' => $tgl,
                    'total_harga_jual' => $totalJual,
                    'total_bayar' => 200000,
                    'total_kembalian' => 50000,
                    'tipe_pembayaran' => 'TUNAI',
                    'status' => 'Lunas',
                    'createdby' => 'Penjaga Toko',
                    'created_at' => $tgl . ' 14:00:00',
                    'updated_at' => $tgl . ' 14:00:00',
                ]);

                // Detail Penjualan
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $barang1->id,
                    'jumlah_jual' => 10,
                    'harga_jual_akhir' => 1500,
                    'sub_total_harga' => 15000,
                    'status' => 'Lunas',
                    'createdby' => 'Penjaga Toko',
                    'created_at' => $tgl . ' 14:00:00',
                ]);

                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $barang4 ? $barang4->id : $barang2->id,
                    'jumlah_jual' => 27,
                    'harga_jual_akhir' => 5000,
                    'sub_total_harga' => 135000,
                    'status' => 'Lunas',
                    'createdby' => 'Penjaga Toko',
                    'created_at' => $tgl . ' 14:00:00',
                ]);

                // Kartu Persediaan Keluar
                KartuPersediaan::create([
                    'id_barang' => $barang1->id,
                    'keterangan' => 'Penjualan Kasir',
                    'no_transaksi' => $penjualan->kode_penjualan,
                    'tanggal' => $tgl,
                    'masuk' => 0,
                    'keluar' => 10,
                    'sisa' => 90,
                    'createdby' => 'Penjaga Toko',
                    'created_at' => $tgl . ' 14:00:00',
                ]);

                // Catat Penerimaan Kas (Debit)
                $lastKas = Kas::orderBy('id', 'desc')->first();
                $saldoSebelumnya = $lastKas ? $lastKas->saldo : 500000;
                Kas::create([
                    'kode_kas' => 'KAS/TA/' . sprintf('%04d', ($idx * 2) + 2),
                    'id_penjualan' => $penjualan->id,
                    'tanggal' => $tgl,
                    'keterangan' => 'Penerimaan Penjualan ' . $penjualan->kode_penjualan,
                    'ref' => $penjualan->kode_penjualan,
                    'debit' => $totalJual,
                    'kredit' => 0,
                    'saldo' => $saldoSebelumnya + $totalJual,
                    'createdby' => 'Penjaga Toko',
                    'created_at' => $tgl . ' 14:00:00',
                ]);
            }
        }

        // 3. Rekap Barang Persediaan
        if (BarangPersediaan::count() == 0) {
            BarangPersediaan::create([
                'id_barang' => $barang1->id,
                'id_supplier' => $supplierIndofood->id,
                'detail' => 'Stok Awal Mie Sedaap Rasa Kare',
                'tanggal_beli' => date('Y-m-d', strtotime('-5 days')),
                'tanggal_jual' => date('Y-m-d'),
                'stok_beli' => 100,
                'stok_jual' => 10,
                'createdby' => 'Administrator',
            ]);

            BarangPersediaan::create([
                'id_barang' => $barang2->id,
                'id_supplier' => $supplierIndofood->id,
                'detail' => 'Stok Awal Indomie Rasa Soto',
                'tanggal_beli' => date('Y-m-d', strtotime('-5 days')),
                'tanggal_jual' => date('Y-m-d'),
                'stok_beli' => 100,
                'stok_jual' => 2,
                'createdby' => 'Administrator',
            ]);
        }
    }
}
