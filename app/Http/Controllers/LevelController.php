<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\levelmodel;
use Yajra\DataTables\Facades\DataTables;



class LevelController extends Controller
{
    public function index(){
    //INSERT DATA 
        //DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?,?,?)', ['CUS','Pelanggan', now()]);
        //return 'Insert data baru berhasil';

    //UPDATE DATA
        //$row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
        //return 'Update data berhasil. Jumlah data yang diupdate: '.$row.' baris';

    //DELETE DATA
        //$row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
        //return 'Delete data berhasil. Jumlah data yang dihapus : '.$row.' baris';
    
    //VIEW DATA
        // $data = DB::select('select * from m_level');
        // return view('level', ['data' => $data]);

        //JOBSHEET 5
        //Menampilkan halaman awal user
        $breadcrumb = (object)[
            'title' => 'Daftar level',
            'list' => ['Home', 'level']
        ];

        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level';//set menu yang sedang aktif

        $level = levelModel::all();// ambil data level untuk filter level

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $level = levelModel::select('level_id', 'level_kode', 'level_nama',);

        //Filter data level berdasarkan level_id
        if ($request->level_id){
            $level->where('level_id',$request->level_id);
        }

        return DataTables::of($level)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                    . csrf_field() 
                    . method_field('DELETE') 
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                
                    return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah level
    public function create(){
        $breadcrummb = (object)[
            'title' => 'Tambah level',
            'list' => ['Home', 'level', 'tambah']
        ];

        $page = (object)[
            'title' => 'Tambah level baru'
        ];

        $level = levelModel::all();//ambil data level untuk ditampilkan di form
        $activeMenu = 'level';
        
        return view('level.create', ['breadcrumb' => $breadcrummb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
    }

    //Menyimpan data user level
    public function store(Request $request){
        $request->validate([
            'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);

        levelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    //Menampilkan detail level
    public function show(string $level_id){
        $level = levelModel::find($level_id);

        $breadcrumb = (object)[
            'title' => 'Detail Level',
            'list' => ['Home', 'level', 'detail']
        ];

        $page = (object)[
            'title' => 'Detail Level'
        ];

        $activeMenu = 'level';

        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    //Menampilkan halaman form edit level
    public function edit(string $level_id){
        $level = levelModel::find($level_id);

        $breadcrumb = (object)[
            'title' => 'Edit Level',
            'list' => ['Home', 'level', 'edit']
        ];

        $page = (object)[
            'title' => 'Edit level'
        ];

        $activeMenu = 'level';

        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $level_id){
        $request->validate([
            'level_kode' => 'required|string|max:5|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);

        $level = levelModel::find($level_id);
        $level->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    //Menghapus data level
    public function destroy(string $level_id){
        $check = levelModel::find($level_id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            levelModel::destroy($level_id); //Hapus data level
        
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            //Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan eror
            return redirect('/level')->with('error', 'Data level gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
