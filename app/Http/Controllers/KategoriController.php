<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\kategorimodel;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

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
        // $data= DB::table('m_kategori')->get();
        // return view('kategori', ['data' => $data]);

        //JOBSHEET 5
        //Menampilkan halaman awal kategori
        $breadcrumb = (object)[
            'title'=>'Daftar kategori barang',
            'list'=>['Home','kategori']
        ];

        $page = (object)[
            'title'=>'Daftar kategori barang yang terdaftar dalam sistem '
        ];

        $activeMenu ='kategori';

        $kategori = kategorimodel::all();

        return view('kategori.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'kategori'=>$kategori]);
    }

    //Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kategori = kategorimodel::select('kategori_id','kategori_kode','kategori_nama');
        
        //Filter data kategori berdasarkan level_id
        if($request->kategori_id){
            $kategori->where('kategori_id',$request->kategori_id);
        }

        return DataTables::of($kategori)
            ->addIndexColumn()// menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">'
                    . csrf_field() 
                    . method_field('DELETE') 
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                
                    return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah kategori
    public function create(){
        $breadcrumb = (object)[
            'title'=>'Tambah kategori barang',
            'list'=>['Home','kategori','tambah']
        ];

        $page = (object)[
            'title'=>'Tambah kategori barang baru'
        ];

        $kategori = kategorimodel::all(); //ambil data kategori untuk ditampilkan di form
        $activeMenu = 'kategori';

        return view('kategori.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'kategori'=>$kategori]);
    
    }

    //Menyimpan data kategori baru
    public function store(Request $request){
        $request->validate([
            'kategori_kode'=>'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama'=>'required|string|max:100'
        ]);

        kategorimodel::create([
            'kategori_kode'=>$request->kategori_kode,
            'kategori_nama'=>$request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success','Data kategori berhasil disimpan');
    }

    //Menampilkan detail kategori
    public function show(string $kategori_id){
        $kategori = kategorimodel::find($kategori_id);

        $breadcrumb = (object)[
            'title'=>'Detail Kategori',
            'list'=>['Home','kategori','detail']
        ];

        $page = (object)[
            'title'=>'Detail kategori'
        ];

        $activeMenu ='kategori';

        return view('kategori.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'kategori'=>$kategori]);
    }

    //Menampilkan halaman form edit kategori
    public function edit(string $kategori_id){
        $kategori = kategorimodel::find($kategori_id);

        $breadcrumb = (object)[
            'title'=>'Edit kategori',
            'list'=>['Home','kategori','edit']
        ];

        $page = (object)[
            'title'=>'Edit kategori'
        ];

        $activeMenu ='kategori';

        return view('kategori.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'kategori'=>$kategori]);
    }

    //Menyimpan perubahan data kategori
    public function update(Request $request, string $kategori_id){
        $request->validate([
            'kategori_kode'=>'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama'=>'required|string|max:100'
        ]);

        $kategori = kategorimodel::find($kategori_id);

        $kategori->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama'=>$request->kategori_nama
        ]);

        return redirect('/kategori')->with('success','Data kategori berhasil diperbarui');
    }

    //Menghapus data kategori
    public function destroy(string $kategori_id){
        $check = kategorimodel::find($kategori_id);
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data level tidak ditemukan');
        }

        try {
            kategorimodel::destroy($kategori_id);//Hapus data kategori

            return redirect('/kategori')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            //Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan eror
            return redirect('/kategori')->with('error', 'Data level gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}