<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;


class BarangController extends Controller
{
    //Menampilkan halaman awal m_barang
    public function index(){
        //JOBSHEET 5
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list'=> ['Home','barang']
        ];

        $page = (object)[
            'title'=> 'Daftar Barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang';

        $kategori = kategoriModel::all(); //ambil data kategori untuk filter kategori 

        return view('barang.index', ['breadcrumb'=> $breadcrumb, 'page' =>$page, 'kategori'=> $kategori, 'activeMenu'=> $activeMenu]);
    }
    
    //Ambil data barang dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
        ->with('kategori');

        //Filter data barang berdasarkan kategori_id
        if($request->kategori_id){
            $barang->where('kategori_id',$request->kategori_id);
        }

        return DataTables::of($barang)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/barang/' . $barang->barang_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/'. $barang->barang_id) . '">'
                    . csrf_field() 
                    . method_field('DELETE') 
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                
                    return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah barang
    public function create(){
        $breadcrumb =(object)[
            'title'=>'Tambah Barang',
            'list'=>['Home', 'data barang']
        ];

        $page =(object)[
            'title'=>'Tambah Barang baru'
        ];

        $kategori = kategoriModel::all();
        $activeMenu = 'kategori';

        return view('barang.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'kategori'=>$kategori]);
    }

    //Menyimpan data barang baru
    public function store(Request $request){
        $request->validate([
            'kategori_id'=>'required|integer',
            'barang_kode'=>'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama'=>'required|string|max:100',
            'harga_beli'=>'required|integer',
            'harga_jual'=>'required|integer'
            
        ]);

        BarangModel::create([
            'kategori_id'=>$request->kategori_id,
            'barang_kode'=>$request->barang_kode,
            'barang_nama'=>$request->barang_nama,
            'harga_beli'=>$request->harga_beli,
            'harga_jual'=>$request->harga_jual
            
        ]);

        return redirect('/barang',)->with('success','Data barang berhasil disimpan');
    }

    //Menampilkan detail barang
    public function show(string $barang_id){
        $barang = BarangModel::with('kategori')->find($barang_id);

        $breadcrumb = (object)[
            'title'=>'Detail barang',
            'list'=>['Home','Data barang','Detail'],
        ];

        $page = (object)[
            'title'=>'Detail data barang'
        ];

        $activeMenu='barang';

        return view('barang.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu, 'barang'=>$barang]);
    }

    //Menampilkan halaman form edit barang
    public function edit(string $barang_id){
        $barang = BarangModel::find($barang_id);
        $kategori = kategoriModel::all();


        $breadcrumb = (object)[
            'title' =>'Edit data barang',
            'list' =>['Home','data barang','edit']
        ];

        $page = (object)[
            'title'=>'Edit data barang'
        ];

        $activeMenu = 'barang';

        return view('barang.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'barang'=>$barang,'kategori'=>$kategori, 'activeMenu'=>$activeMenu]);
    }

    //Menyimpan perubahan data barang
    public function update(Request $request, string $barang_id){
        $request->validate([
            'kategori_id'=>'required|integer',
            'barang_kode'=>'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama'=>'required|string|max:100',
            'harga_beli'=>'required|integer',
            'harga_jual'=>'required|integer'
        ]);

        $barang = BarangModel::find($barang_id);
        $barang->update([
            'kategori_id'=>$request->kategori_id,
            'barang_kode'=>$request->barang_kode,
            'barang_nama'=>$request->barang_nama,
            'harga_beli'=>$request->harga_beli,
            'harga_jual'=>$request->harga_jual
        ]);

        return redirect('/barang')->with('success','Data barang berhasil diubah');
    }

    //Menghapus data barang
    public function destroy(string $barang_id){
        $check = BarangModel::find($barang_id);
        if(!$check){
            return redirect('/barang')->with('error','Data user tidak ditemukan');
        }

        try{
            BarangModel::destroy($barang_id);

            return redirect('/barang')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            //Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan eror
            return redirect('/barang')->with('error','Data user gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}