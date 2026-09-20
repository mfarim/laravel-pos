<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\User;
use App\Barang;
use App\Supplier; 

class PemilikController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $user = $request->user();

        return view('pemilik.index',['user'=>$user]);
    }

    public function tambahsupplier(Request $request)
    {
        $user = $request->user();
        $prefix = DB::table('config')->select('value')->where('key','prefix_supplier')->first()->value;            
        $data = Supplier::count();              
        $prx=$prefix;
        if($data>0)
        {            
          $kode_supplier = Supplier::orderBy('id', 'desc')->first()->kode_supplier;      
          $kd_max = Str::substr($kode_supplier, 4);  
          $tmp = ((int)$kd_max)+1;
          $kd = $prx.sprintf("-%04s", $tmp);            
        }
        else
        {
          $kd = $prx."-0001";
        }
        if ($user->hasRole('pemilik')) {
            return view('supplier.tambah', ['user'=>$user, 'kode_supplier' => $kd]);
        } else {
            # code...
        }
    }


        public function ngubahsupplier(Request $request, $id)
        {
            $user = $request->user();
            
            if ($user->hasRole('pemilik')) {
                $supplier = Supplier::where('id',$id)->first();
                return view('supplier.ubah', ['supplier' => $supplier]);
            } else {
                # code...
            }
        }

        public function tambahpenjaga(Request $request)
        {
            $user = $request->user();
            if ($user->hasRole('pemilik')) {
                return view('penjaga.tambah');
            } else {
                # code...
            }
        }
         

        public function ngubahpenjaga(Request $request, $id)
        {
            $user = $request->user();
            if ($user->hasRole('pemilik')) {
                $penjaga = User::where('id', $id)
                    ->where(function ($q) use ($user) {
                        if (!$user->isSuperAdmin()) {
                            $q->where('tenant_id', $user->tenant_id);
                        }
                    })->firstOrFail();
                return view('penjaga.ubah',['penjaga'=>$penjaga]);
            }
        }

        public function datapenjaga(Request $request, Builder $htmlBuilder){
          $user = $request->user();
          if ($user->hasRole('penjaga') || $user->hasRole('pemilik')) {
            if($user->hasRole('pemilik')){
              if ($request->ajax()) {
                  $penjaga = User::select(['id','name','username','email'])
                      ->where(function ($q) use ($user) {
                          if (!$user->isSuperAdmin()) {
                              $q->where('tenant_id', $user->tenant_id);
                          }
                      });
                  return DataTables::of($penjaga)
                  ->addColumn('action', function ($penjaga) {
                      return view('datatable._penjagaTb', [
                      'model' => $penjaga,
                      'form_url' => route('hapuspenjaga', $penjaga->id),
                      'edit_url'=> route('ngubahpenjaga', $penjaga->id)]);
                  })
                    ->addColumn('content', function ($penjaga) {
                        return strip_tags($penjaga->content);
                    })
                  ->addIndexColumn()
                  ->make(true);
              }
            }


              $html = $htmlBuilder->addIndex(['title'=>'Nomor'])
            ->addColumn(['data'=>'name', 'name'=>'name', 'title'=>'Nama Penjaga'])
            ->addColumn(['data'=>'username', 'name'=>'username', 'title'=>'username'])
            ->addColumn(['data'=>'email', 'name'=>'email', 'title'=>'Email'])
            ->addColumn(['data' => 'action','name' => 'action', 'title'=> 'Lainnya', 'orderable' => false, 'searchable' => false]);

              return view('penjaga.datapenjaga', ['user'=>$user])->with(compact('html'));
          }
        }
}
