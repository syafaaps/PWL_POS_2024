<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriModel;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // JOBSHEET 6
    //Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id','kategori_kode','kategori_nama');
        
        //Filter data kategori berdasarkan level_id
        if($request->kategori_id){
            $kategori->where('kategori_id',$request->kategori_id);
        }

        return DataTables::of($kategori)
            ->addIndexColumn()// menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                $btn = '<a href="' .url('/kategori/' . $kategori->kategori_id). '" class="btn btn-info btnsm">Detail</a> ';
                // $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">'
                //     . csrf_field() 
                //     . method_field('DELETE') 
                //     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                
                //$btn = '<button onclick="modalAction(\'' . url('/level/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                    return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
        }
        public function create_ajax(){
            return view('kategori.create_ajax');
        }

        public function store_ajax(Request $request){
            //cek apaakah request berupa ajax
            if($request->ajax() || $request->wantsJson()){
                $rules = [
                    'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
                    'kategori_nama' => 'required|string|max:100'
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
                
                KategoriModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil disimpan'
                ]);
            }
            return redirect('/');
        }

        // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $kategori = KategoriModel::find($id);
        
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    // Menampilkan halaman form update user ajax
    public function update_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode'  => 'required|string|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama'  => 'required|string|max:100'
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
    
            $check = KategoriModel::find($id);
            
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
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
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

    public function import()
    {
        return view('kategori.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_kategori'); // ambil file dari request
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
                            'kategori_kode' => $value['A'],
                            'kategori_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    KategoriModel::insertOrIgnore($insert);
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
        // ambil data kategori yang akan di export
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')
            ->orderBy('kategori_kode')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode kategori');
        $sheet->setCellValue('C1', 'Nama kategori');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold header
        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($kategori as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kategori_kode);
            $sheet->setCellValue('C' . $baris, $value->kategori_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }
        $sheet->setTitle('Data kategori'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data kategori ' . date('Y-m-d H:i:s') . '.xlsx';
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
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')
            ->orderBy('kategori_kode')
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream('Data kategori' . date('Y-m-d H:i:s') . '.pdf');
    }
}