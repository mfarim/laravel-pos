<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;
use App\User;
use App\Barang;
use App\BarangKategori;
use App\BarangSatuan;
use App\Pembelian;
use App\Penjualan;
use App\Supplier;
use App\Role;
use App\DetailPembelianTBS;
use App\DetailPenjualanTBS;

class PenjagaController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            return view('penjaga.index', ['user'=>$user]);
        } else {
            # code...
        }
    }

    public function generate_barcode()
      {          
        $prefix = DB::table('config')->select('value')->where('key','prefix_barcode')->first()->value;            
        $data = Barang::count();              
        $prx=$prefix;
        if($data>0)
        {            
          $kode_barang = Barang::orderBy('id', 'desc')->first()->kode_barang;      
          $kd_max = Str::substr($kode_barang, 4);  
          $tmp = ((int)$kd_max)+1;
          $kd = $prx.sprintf("-%04s", $tmp);            
        }
        else
        {
          $kd = $prx."-0001";
        } 
        echo json_encode(array("return" => $kd));                     
      }

    public function generate_no_pembelian()
      {          
        $prefix = DB::table('config')->select('value')->where('key','prefix_kode_pembelian')->first()->value;  
        $data = Pembelian::count();                    
        $prx=$prefix;
        if($data>0)
        {                        
          $kode_pembelian = Pembelian::orderBy('id', 'desc')->first()->kode_pembelian;      
          $kd_max = Str::substr($kode_pembelian, 6);        
          $tmp = ((int)$kd_max)+1;
          $kd = $prx.sprintf("%04s", $tmp);            
        }
        else
        {
            $kd = $prx."0001";
        } 
        echo json_encode(array("return" => $kd));                     
      }

    public function generate_no_penjualan()
      {          
        $prefix = DB::table('config')->select('value')->where('key','prefix_kode_penjualan')->first()->value;
        $data = Penjualan::count();                    
        $prx=$prefix;
        if($data>0)
        {                        
          $kode_penjualan = Penjualan::orderBy('id', 'desc')->first()->kode_penjualan;      
          $kd_max = Str::substr($kode_penjualan, 6);        
          $tmp = ((int)$kd_max)+1;
          $kd = $prx.sprintf("%04s", $tmp);            
        }
        else
        {
            $kd = $prx."0001";
        }    
        echo json_encode(array("return" => $kd));                     
      }

    //Barang
    public function tambahbarang(Request $request)
    {     
      $user = $request->user();
      if ($user->hasRole('penjaga')) {
        $barang_kategori = BarangKategori::select(DB::raw("idbarang_kategori, kategori"))->orderBy(DB::raw("idbarang_kategori"))->get();
        $barang_unit = BarangSatuan::select(DB::raw("idbarang_unit, unit"))->orderBy(DB::raw("idbarang_unit"))->get();
        return view('barang.tambah', ['user'=>$user,'barang_kategori'=>$barang_kategori,'barang_unit'=>$barang_unit]);
      }
    }

    public function ngubahbarang(Request $request, $id)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            $barang_kategori = BarangKategori::select(DB::raw("idbarang_kategori, kategori"))->orderBy(DB::raw("idbarang_kategori"))->get();
            $barang_unit = BarangSatuan::select(DB::raw("idbarang_unit, unit"))->orderBy(DB::raw("idbarang_unit"))->get();
            $barang = Barang::find($id);
            $kategori = BarangKategori::where('idbarang_kategori', $barang->idbarang_kategori)->first()->kategori;
            $satuan = BarangSatuan::where('idbarang_unit', $barang->idbarang_unit)->first()->unit;
            return view('barang.ubah', ['barang' => $barang,'barang_kategori'=>$barang_kategori,'barang_unit'=>$barang_unit,'kategori'=>$kategori,'satuan'=>$satuan]);
        } else {
            # code...
        }
    }
    
    //Kategori Barang 
    public function tambahbarang_kategori(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            return view('barang_kategori.tambah', ['user'=>$user]);
        }
    }
    
    public function ngubahbarang_kategori(Request $request, $id)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            $barang_kategori = BarangKategori::find($id);
            return view('barang_kategori.ubah', ['barang_kategori' => $barang_kategori]);
        } else {
            # code...
        }
    }

    //Satuan Barang 
    public function tambahbarang_satuan(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            return view('barang_satuan.tambah', ['user'=>$user]);
        }
    }
    
    public function ngubahbarang_satuan(Request $request, $id)
    {
        $user = $request->user();
        // dd($id);
        if ($user->hasRole('penjaga')) {

            $barang_satuan = BarangSatuan::find($id);
            return view('barang_satuan.ubah', ['barang_satuan' => $barang_satuan]);
        } else {
            # code...
        }
    }

    //Supplier
    public function tambahsupplier(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            return view('supplier.tambah', ['user'=>$user]);
        } else {
            # code...
        }
    }

    public function ngubahsupplier(Request $request, $id)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
            $supplier = Supplier::find($id);
            return view('supplier.ubah', ['supplier' => $supplier]);
        } else {
            # code...
        }
    }

    public function tambahpenjualan(Request $request)
    {
      $prefix = DB::table('config')->select('value')->where('key','prefix_kode_penjualan')->first()->value;
      $data = Penjualan::count();                    
      $prx=$prefix;
      if($data>0)
      {                        
        $kode_penjualan = Penjualan::orderBy('id', 'desc')->first()->kode_penjualan;      
        $kd_max = Str::substr($kode_penjualan, 6);        
        $tmp = ((int)$kd_max)+1;
        $kd = $prx.sprintf("%04s", $tmp);            
      }
      else
      {
          $kd = $prx."0001";
      }  
      $user = $request->user();
      if ($user->hasRole('penjaga')) {           
          $tglPenjualan = date("Y-m-d");          
          $tbs_penjualans = DetailPenjualanTBS::select(DB::raw("tbs_penjualan_details.id, nama_barang, harga_jual, barang.harga_jual_akhir, jumlah_jual, sub_total_harga, diskon"))
                ->join('barang', 'tbs_penjualan_details.id_barang', '=', 'barang.id')
                ->get();
          // dd($tbs_pembelians);  
          $total_harga_jual = 0;
          foreach ($tbs_penjualans as $detail_barang) {
            $total_harga_jual += $detail_barang->sub_total_harga;
          }
          return view('penjualan.tambah', ['user'=>$user,'tglPenjualan'=>$tglPenjualan,'kodePenjualan'=>$kd,'detailPenjualan'=>$tbs_penjualans, 'total_harga_jual'=>$total_harga_jual]);
      } else {
          # code...
      }
    }   

    public function tambahreturpenjualan(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
          // $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          // $shuffled = str_shuffle($str);
          // $randstr = substr($shuffled, 0, 5);
          // $kodePenjualan = "RP".''.date("Ymd").''.$randstr;
          $kode = DB::table('returpenjualan')->max('no_retur');
          $nourut = substr($kode, 2, 4); // contoh JRD0004, angka 3 adalah awal pengambilan angka, dan 4 jumlah angka yang diambil
          $nourut++;
          $no = sprintf("%04s", $nourut);
          $kodePenjualan = "RJ".''.$no;

          return view('returpenjualan.tambah', ['user'=>$user,'kodePenjualan'=>$kodePenjualan]);
        } else {
            # code...
        }
    }

    public function tambahreturpembelian(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('penjaga')) {
          // $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          // $shuffled = str_shuffle($str);
          // $randstr = substr($shuffled, 0, 5);
          // $kodePenjualan = "RB".''.date("Ymd").''.$randstr;
          $kode = DB::table('returpembelian')->max('no_retur');
          $nourut = substr($kode, 2, 4); // contoh JRD0004, angka 3 adalah awal pengambilan angka, dan 4 jumlah angka yang diambil
          $nourut++;
          $no = sprintf("%04s", $nourut);
          $kodePenjualan = "RB".''.$no;

          return view('returpembelian.tambah', ['user'=>$user,'kodePenjualan'=>$kodePenjualan]);
        } else {
            # code...
        }
    }

    public function tambahpembelian(Request $request)
        {
            $prefix = DB::table('config')->select('value')->where('key','prefix_kode_pembelian')->first()->value;  
            $data = Pembelian::count();                    
            $prx=$prefix;
            if($data>0)
            {                        
              $kode_pembelian = Pembelian::orderBy('id', 'desc')->first()->kode_pembelian;      
              $kd_max = Str::substr($kode_pembelian, 6);        
              $tmp = ((int)$kd_max)+1;
              $kd = $prx.sprintf("%04s", $tmp);            
            }
            else
            {
                $kd = $prx."0001";
            }
            $user = $request->user();
            if ($user->hasRole('pemilik')) {  
              $tglPembelian = date("d-m-Y");  
              $tbs_pembelians = DetailPembelianTBS::select(DB::raw("tbs_pembelian_details.id, nama_barang, harga_beli, jumlah_beli, sub_total_harga"))
                    ->join('barang', 'tbs_pembelian_details.id_barang', '=', 'barang.id')
                    ->get();
              // dd($tbs_pembelians);  
              $total_harga_beli = 0;
              foreach ($tbs_pembelians as $detail_barang) {
                $total_harga_beli += $detail_barang->sub_total_harga;
              }
              // dd($total_harga_beli);
              $supplier = Supplier::all();
            return view('pembelian.tambah', ['user'=>$user,'tglPembelian'=>$tglPembelian,'kodePembelian'=>$kd,'detailPembelian'=>$tbs_pembelians, 'total_harga_beli'=>$total_harga_beli, 'list_supplier'=>$supplier]);
            } else {
                # code...
            }
        }

        public function searchbarang(Request $request)
        {
        	$data = [];

            if($request->has('q')){
                $search = $request->q;
                $data = Barang::select("id","kode_barang","nama_barang","harga_beli","harga_jual","stok", "diskon", "harga_jual_akhir")
                		->where('nama_barang','LIKE',"%$search%")
                    ->orWhere('kode_barang','LIKE',"%$search%")
                		->get();
            }

            return response()->json($data);
        }

        public function searchidbarang(Request $request)
        {
          $data = [];

            if($request->has('q')){
                $search = $request->q;
                $data = Barang::select("id","kode_barang","nama_barang","harga_jual","diskon")
                    ->where('kode_barang','LIKE',"%$search%")
                    ->get();
            }

            return response()->json($data);
        }        

        public function searchsupplier(Request $request)
        {
          $data = [];

            if($request->has('q')){
                $search = $request->q;
                $data = Supplier::select("id","nama_supplier")
                    ->where('nama_supplier','LIKE',"%$search%")
                    ->get();
            }

            return response()->json($data);
        }
        public function hapuspenjaga(Request $request, $id){
          $user = $request->user();
          User::destroy($id);
          Session::flash("notif", [
            "level"=>"info",
            "message"=>"Data Penjaga berhasil dihapus"
            ]);
            if ($user->hasRole('pemilik')) {
                return redirect()->route('datapenjaga');
            } else {
                //return buat pemilik
            }
        }

        public function simpanpenjaga(Request $request){
          $user = $request->user();

          if ($user->hasRole('penjaga') || $user->hasRole('pemilik')) {
              $this->validate($request, ['name'=>'required',
                                    'username'=>'required|max:12',
                                    'email'=>'required',
                                    'password'=>'required']);
              $posts = User::create(['name'=> $request->name,
                                    'username'=>$request->username,
                                    'email'=>$request->email,
                                    'password'=>bcrypt($request->password)]);
                                    $memberRole = Role::where('name', 'penjaga')->first();
                                    $posts->attachRole($memberRole);
                                    // dd($posts);

              Session::flash("notif", [
                         "level"=>"success",
                         "message"=>"Data Penjaga Berhasil Tersimpan"
                         ]);
              if ($user->hasRole('pemilik')) {
                  return redirect()->route('datapenjaga');
              } else {
                  //return buat pemilik
              }
          }
        }
}
