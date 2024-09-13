<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(){
        //INSERT DATA
        /*$data = [
             'kategori_kode' => 'SNK',
             'kategori_nama' => 'Snack/Makanan Ringan',
             'created_at' => now()
         ];
         DB::table('m_kategori')->insert($data);
         return 'Insert data baru berhasil';*/

       //UPDATE DATA
       /* $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
        return 'Update data berhasil. Jumlah data yang diupdate : ' . $row.' baris';*/

        //DELETE DATA
        /* $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
         return 'Delete data berhasil. Jumlah data yang dihapus : ' . $row.' baris';*/
        
        //VIEW DATA
        $data= DB::table('m_kategori')->get();
        return view('kategori', ['data' => $data]);
    }
}