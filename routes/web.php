<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
  return view('landing');
})->name('landing');

Route::get('/auth/google', 'Auth\GoogleAuthController@redirectToGoogle')->name('auth.google');
Route::get('/auth/google/callback', 'Auth\GoogleAuthController@handleGoogleCallback')->name('auth.google.callback');
Route::group(['middleware' => ['auth']], function (){
  //grafikpenjualan
  Route::get('/grafik','laporanController@grafikpenjualan')->name('grafikpenjualan');
  Route::get('/grafik/lihat','laporanController@lihatgrafik')->name('lihatgrafik');
});

Route::group(['prefix' => 'pemilik', 'middleware' => ['auth','role:pemilik']], function () {
	Route::get('/', function(){
	  return redirect()->route('grafikpenjualan');
	});

	//Supplier
	Route::get('/supplier', 'supplierController@datasupplier')->name('datasupplier');
	Route::post('/supplier/ubah/{id}', 'supplierController@ubahsupplier')->name('ubahsupplier');
	Route::get('/supplier/tambah', 'PenjagaController@tambahsupplier')->name('tambahsupplier');
	Route::get('/supplier/ubah/{id}','PenjagaController@ngubahsupplier')->name('ngubahsupplier');
	Route::post('/supplier/tambah', 'supplierController@simpansupplier')->name('ketambahsupplier');
	Route::delete('/supplier/{id}','supplierController@hapussupplier')->name('hapussupplier');

	//Penjaga
	Route::get('/penjaga', 'PemilikController@datapenjaga')->name('datapenjaga');
	Route::get('/penjaga/tambah', 'PemilikController@tambahpenjaga')->name('tambahpenjaga');
	Route::post('/penjaga/tambah', 'PenjagaController@simpanpenjaga')->name('simpanpenjaga');
	Route::get('/penjaga/ubah/{id}', 'PemilikController@ngubahpenjaga')->name('ngubahpenjaga');
	Route::post('/penjaga/ubah/{id}', 'PenjagaController@ubahpenjaga')->name('ubahpenjaga');
	Route::delete('/penjaga/{id}','PenjagaController@hapuspenjaga')->name('hapuspenjaga');

	//Supplier
	Route::get('/supplier', 'supplierController@datasupplier')->name('datasupplierpm');
	Route::post('/supplier/ubah/{id}', 'supplierController@ubahsupplier')->name('ubahsupplierpm');
	Route::get('/supplier/tambah', 'PemilikController@tambahsupplier')->name('tambahsupplierpm');
	Route::get('/supplier/ubah/{id}','PemilikController@ngubahsupplier')->name('ngubahsupplierpm');
	Route::post('/supplier/tambah', 'supplierController@simpansupplier')->name('ketambahsupplierpm');
	Route::delete('/supplier/{id}','supplierController@hapussupplier')->name('hapussupplierpm');


	//Laporan Penjualan
	Route::get('/laporan/penjualan','laporanController@penjualan')->name('laporanpenjualanpm');
	Route::get('/laporan/penjualan/lihat','laporanController@lihatpenjualan')->name('lihatlaporanpenjualanpm');
	Route::get('/laporan/penjualan/cetak={awal}&{akhir}','laporanController@cetak_penjualan')->name('cetaktlaporanpenjualanpm');

	//Laporan Persediaan
	Route::get('/laporan/persediaan','laporanController@persediaan')->name('laporanpersediaanpm');
	Route::get('/laporan/persediaan/lihat','laporanController@lihatpersediaan')->name('lihatlaporanpersediaanpm');
	Route::get('/laporan/persediaan/cetak={awal}&{akhir}&{id_barang}','laporanController@cetak_persediaan')->name('cetakpersediaanpm');
	Route::get('/laporan/persediaan/cetakkartupersediaan={awal}&{akhir}&{id_barang}','laporanController@cetak_kartu_persediaan')->name('cetakkartupersediaanpm');

	//Kas
	Route::get('/laporan/kas', 'kasController@index')->name('datakaspm');
	Route::get('/laporan/kas/lihat', 'kasController@lihat_tanggal')->name('lihat_tanggalkaspm');
	Route::get('/laporan/kas/cetak={awal}&{akhir}','kasController@cetak_kas')->name('cetaktlaporankaspm');

	//Pembelian
	Route::get('/pembelian', 'pembelianController@datapembelian')->name('datapembelian');
	Route::get('/pembelian/tambah', 'PenjagaController@tambahpembelian')->name('tambahpembelian');
	Route::get('/pembelian/lihat', 'pembelianController@lihat_tanggal')->name('lihat_tanggalpembelian');

	// Route::get('/pembelian/lihat/cetak/{id}', 'pembelianController@cetak')->name('cetakpembelian');
	Route::get('/pembelian/lihat/cetak={awal}&{akhir}','pembelianController@cetak')->name('cetakpembelian');

	Route::get('/pembelian/lihat/nota/{id}', 'pembelianController@lihat_nota')->name('nota_pembelian');
	Route::post('/pembelian/tambah', 'pembelianController@simpanpembelian')->name('simpanpembelian');
	// Route::get('/pembelian/tambah/{id}/edit', 'pembelianController@simpanpembelian_editdetail')->name('simpanpembelian_editdetail');
	Route::get('/pembelian/tambah/{id}/hapus', 'pembelianController@simpanpembelian_hapusdetail')->name('simpanpembelian_hapusdetail');
	Route::delete('/pembelian/lihat/{id}','pembelianController@hapuspembelian')->name('hapuspembelian');

	// Sarch
	Route::get('/barang/search', 'PenjagaController@searchbarang')->name('searchbarang');
});

Route::group(['prefix' => 'penjaga', 'middleware' => ['auth','role:penjaga']], function () {
	//
	Route::get('/', function(){
	  return redirect()->route('grafikpenjualan');
	});

	//Barang
	Route::get('/barang', 'barangController@barang')->name('databarang');
	Route::post('/barang/ubah/{id}', 'barangController@ubahbarang')->name('ubahbarang');
	Route::get('/barang/tambah', 'PenjagaController@tambahbarang')->name('tambahbarang');
	Route::get('/barang/ubah/{id}','PenjagaController@ngubahbarang')->name('ngubahbarang');
	Route::post('/barang/tambah', 'barangController@simpanbarang')->name('ketambahbarang');
	Route::delete('/barang/{id}','barangController@hapusbarang')->name('hapusbarang');

	//Barang Kategori
	Route::get('/barang_kategori', 'barangKategoriController@barang_kategori')->name('databarang_kategori');
	Route::post('/barang_kategori/ubah/{id}', 'barangKategoriController@ubahbarang_kategori')->name('ubahbarang_kategori');
	Route::get('/barang_kategori/tambah', 'PenjagaController@tambahbarang_kategori')->name('tambahbarang_kategori');
	Route::get('/barang_kategori/ubah/{id}','PenjagaController@ngubahbarang_kategori')->name('ngubahbarang_kategori');
	Route::post('/barang_kategori/tambah', 'barangKategoriController@simpanbarang_kategori')->name('ketambahbarang_kategori');
	Route::delete('/barang_kategori/{id}','barangKategoriController@hapusbarang_kategori')->name('hapusbarang_kategori');

	//Barang Satuan
	Route::get('/barang_satuan', 'barangSatuanController@barang_satuan')->name('databarang_satuan');
	Route::post('/barang_satuan/ubah/{id}', 'barangSatuanController@ubahbarang_satuan')->name('ubahbarang_satuan');
	Route::get('/barang_satuan/tambah', 'PenjagaController@tambahbarang_satuan')->name('tambahbarang_satuan');
	Route::get('/barang_satuan/ubah/{id}','PenjagaController@ngubahbarang_satuan')->name('ngubahbarang_satuan');
	Route::post('/barang_satuan/tambah', 'barangSatuanController@simpanbarang_satuan')->name('ketambahbarang_satuan');
	Route::delete('/barang_satuan/{id}','barangSatuanController@hapusbarang_satuan')->name('hapusbarang_satuan');

	//Penjualan
	Route::get('/penjualan', 'penjualanController@datapenjualan')->name('datapenjualan');
	Route::get('/penjualan/lihat', 'penjualanController@lihat_tanggal')->name('lihat_tanggalpenjualan');
	Route::get('/penjualan/lihat/nota/{id}', 'penjualanController@lihat_nota')->name('nota_penjualan');
	Route::get('/penjualan/lihat/cetak/{id}', 'penjualanController@cetak')->name('cetakpenjualan');
	Route::get('/penjualan/tambah', 'PenjagaController@tambahpenjualan')->name('tambahpenjualan');
	Route::post('/penjualan/tambah', 'penjualanController@simpanpenjualan')->name('simpanpenjualan');
	// Route::get('/penjualan/tambah/{id}/edit', 'penjualanController@simpanpenjualan_editdetail')->name('simpanpenjualan_editdetail');
	Route::get('/penjualan/tambah/{id}/hapus', 'penjualanController@simpanpenjualan_hapusdetail')->name('simpanpenjualan_hapusdetail');
	Route::delete('/penjualan/lihat/{id}','penjualanController@hapuspenjualan')->name('hapuspenjualan');

	//Retur Penjualan
	Route::get('/returpenjualan', 'returpenjualanController@datareturpenjualan')->name('datareturpenjualan');
	Route::get('/returpenjualan/lihat', 'returpenjualanController@lihat_tanggal')->name('lihat_tanggalreturpenjualan');
	Route::get('/returpenjualan/cetak={id}', 'returpenjualanController@cetak')->name('cetakreturpenjualan');
	Route::get('/returpenjualan/tambah', 'PenjagaController@tambahreturpenjualan')->name('tambahreturpenjualan');
	Route::post('/returpenjualan/tambah', 'returpenjualanController@simpanreturpenjualan')->name('simpanreturpenjualan');
	Route::delete('/returpenjualan/lihat/{id}','returpenjualanController@hapusretur')->name('hapusreturpenjualan');

	//Retur Pembelian
	Route::get('/returpembelian', 'returpembelianController@datareturpembelian')->name('datareturpembelian');
	Route::get('/returpembelian/lihat', 'returpembelianController@lihat_tanggal')->name('lihat_tanggalreturpembelian');
	Route::get('/returpembelian/cetak={id}', 'returpembelianController@cetak')->name('cetakreturpembelian');
	Route::get('/returpembelian/tambah', 'PenjagaController@tambahreturpembelian')->name('tambahreturpembelian');
	Route::post('/returpembelian/tambah', 'returpembelianController@simpanreturpembelian')->name('simpanreturpembelian');
	Route::delete('/returpembelian/lihat/{id}','returpembelianController@hapusretur')->name('hapusreturpembelian');

	//ETC
	Route::get('/barang/search', 'PenjagaController@searchbarang')->name('searchbarang_penjaga');
	Route::get('/barang/searchid', 'PenjagaController@searchidbarang')->name('searchidbarang');
	Route::get('/barang/generate_barcode', 'PenjagaController@generate_barcode')->name('generate_barcode');
	Route::get('/pembelian/generate_barcode', 'PenjagaController@generate_no_pembelian')->name('generate_no_pembelian');
	Route::get('/penjualan/generate_barcode', 'PenjagaController@generate_no_penjualan')->name('generate_no_penjualan');
	Route::get('/supplier/search', 'PenjagaController@searchsupplier')->name('searchsupplier');

	//Laporan Penjualan
	Route::get('/laporan/penjualan','laporanController@penjualan')->name('laporanpenjualan');
	Route::get('/laporan/penjualan/lihat','laporanController@lihatpenjualan')->name('lihatlaporanpenjualan');
	Route::get('/laporan/penjualan/cetak={awal}&{akhir}','laporanController@cetak_penjualan')->name('cetaktlaporanpenjualan');
	//Laporan Persediaan
	Route::get('/laporan/persediaan','laporanController@persediaan')->name('laporanpersediaan');
	Route::get('/laporan/persediaan/lihat','laporanController@lihatpersediaan')->name('lihatlaporanpersediaan');
	Route::get('/laporan/persediaan/cetak={awal}&{akhir}','laporanController@cetak_persediaan')->name('cetakpersediaan');
	Route::get('/laporan/persediaan/cetakkartupersediaan={awal}&{akhir}&{id_barang}','laporanController@cetak_kartu_persediaan')->name('cetakkartupersediaan');

	//kas
	Route::get('/laporan/kas', 'kasController@index')->name('datakas');
	Route::get('/laporan/kas/lihat', 'kasController@lihat_tanggal')->name('lihat_tanggalkas');
	Route::get('/laporan/kas/cetak={awal}&{akhir}','kasController@cetak_kas')->name('cetaktlaporankas');
});

Auth::routes();
