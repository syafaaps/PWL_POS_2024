<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\levelmodel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;



class LevelController extends Controller
{
    // JOBSHEET 6
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
                    // $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                    // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                    //     . csrf_field() 
                    //     . method_field('DELETE') 
                    //     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 
                    //$btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
                         return $btn;
                    
                })
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
                ->make(true);
        }
    
        public function create_ajax(){
            return view('level.create_ajax');
        }

        public function store_ajax(Request $request){
            //cek apsakah request berupa ajax
            if($request->ajax() || $request->wantsJson()){
                $rules = [
                    'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
                    'level_nama' => 'required|string|max:100'
                ];
                // use Illuminate\Support\Facades\Validator;
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                    ]);
                }  
                
                LevelModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil disimpan'
                ]);
            }
            return redirect('/');
        }

        // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $level = LevelModel::find($id);
        
        return view('level.edit_ajax', ['level' => $level]);
    }

    // Menampilkan halaman form update user ajax
    public function update_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100'
            ];
    
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
    
            $check = LevelModel::find($id);
            
            if ($check) {
    
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        
        return redirect('/');
    }
    
    public function confirm_ajax(string $id) {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    
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
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Level', 'url' => url('/level')],
            ]
        ];

        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level';//set menu yang sedang aktif

        $level = levelModel::all();// ambil data level untuk filter level

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
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
                'list' =>[
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Level', 'url' => url('/level')],
                ['name' => 'Level', 'url' => url('/detail')]
            ]
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
                'list' =>[
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Level', 'url' => url('/level')],
                ['name' => 'Level', 'url' => url('/edit')]
            ]
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

        public function import()
    {
        return view('level.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_level' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_level'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'level_kode' => $value['A'],
                            'level_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    LevelModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }
    public function export_excel()
    {
        // ambil data level yang akan di export
        $level = LevelModel::select('level_kode', 'level_nama')
            ->orderBy('level_kode')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode level');
        $sheet->setCellValue('C1', 'Nama level');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold header
        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($level as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->level_kode);
            $sheet->setCellValue('C' . $baris, $value->level_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }
        $sheet->setTitle('Data level'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data level ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    } // end function export_excel
    public function export_pdf()
    {
        $level = LevelModel::select('level_kode', 'level_nama')
            ->orderBy('level_kode')
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream('Data level' . date('Y-m-d H:i:s') . '.pdf');
    }
    
    }
