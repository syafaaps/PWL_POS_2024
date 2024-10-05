<?php

namespace App\Http\Controllers;

use App\Models\suppliermodel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class suppliercontroller extends Controller
{
    //JOBSHEET 6
    //Ambil data supplier dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $supplier = suppliermodel::select('supplier_id','supplier_kode','supplier_nama','supplier_alamat');
        
        if($request->supplier_id){
            $supplier->where('supplier_id',$request->supplier_id);
        }

        return DataTables::of($supplier)
            ->addIndexColumn()// menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btnsm">Detail</a> ';
                // $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
        }
        
        public function create_ajax(){
            return view('supplier.create_ajax');
        }
        public function store_ajax(Request $request){
            //cek apsakah request berupa ajax
            if($request->ajax() || $request->wantsJson()){
                $rules = [                 
                    'supplier_kode'    => 'required|string|max:10|unique:m_supplier,supplier_kode',
                    'supplier_nama'    => 'required|string|max:100',     
                    'supplier_alamat'  => 'required|string|max:255' 
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                    ]);
                }  
                
                SupplierModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil disimpan'
                ]);
            }
            return redirect('/');
        }
        public function edit_ajax(string $id){
            $supplier = SupplierModel::find($id);
            return view('supplier.edit_ajax', ['supplier' => $supplier]);
        }
        public function update_ajax(Request $request, $id) {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'supplier_kode'    => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
                    'supplier_nama'    => 'required|string|max:100',     
                    'supplier_alamat'  => 'required|string|max:255' 
                ];
        
                
                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // respon json, true: berhasil, false: gagal
                        'message' => 'Validasi gagal.',
                        'msgField' => $validator->errors() // menunjukkan field mana yang error
                    ]);
                }
        
                $check = SupplierModel::find($id);
                
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
            $supplier = SupplierModel::find($id);
            return view('supplier.confirm_ajax', ['supplier' => $supplier]);
        }
        public function delete_ajax(Request $request, $id){
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $supplier = SupplierModel::find($id);
                if ($supplier) {
                    $supplier->delete();
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

    //Menampilkan halaman awal supplier
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar supplier',
            'list'=>['Home','supplier']
        ];

        $page =(object)[
            'title'=>'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu ='supplier';

        $supplier = suppliermodel::all();

        return view('supplier.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier, 'activeMenu' =>$activeMenu]);
    }

    //Menampilkan halaman form tambah supllier
    public function create(){
        $breadcrumb = (object)[
            'title'=>'Tambah supplier',
            'list'=>['Home','supplier','tambah']
        ];
        
        $page = (object)[
            'title'=>'Tambah supplier baru'
        ];

        $activeMenu = 'supplier';
        $supplier = suppliermodel::all();

        return view('supplier.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    //Menyimpan data supplier baru
    public function store(Request $request){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);

        suppliermodel::create([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat,
        ]);

        return redirect('/supplier')->with('success','Data kategori berhasil disimpan');
    }

    //Menampilkan detail supplier
    public function show(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);

        $breadcrumb = (object)[
            'title'=>'Detail supplier',
            'list'=>['Home','supplier','detail']
        ];

        $page = (object)[
            'title'=>'Detail supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    //Menampilkan halaman form edit supplier
    public function edit(string $supplier_id){
        $supplier = suppliermodel::find($supplier_id);

        $breadcrumb = (object)[
            'title'=>'Edit supplier',
            'list'=>['Home','supplier','edit']
        ];

        $page = (object)[
            'title' => 'Edit supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier,'activeMenu'=>$activeMenu]);
    }

    //Menyimpan perubahan data supplier
    public function update(Request $request, string $supplier_id){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:5|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);

        $supplier = suppliermodel::find($supplier_id);
        $supplier->update([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat
        ]);
        
        return redirect('/supplier')->with('success','Data supplier berhasil diperbarui');
    }

    //Menghapus data user
    public function destroy(string $supplier_id){
        $check = suppliermodel::find($supplier_id);
        if (!$check) {
            return redirect('/supplier')->with('error', 'Data level tidak ditemukan');
        }
        
        try {
            suppliermodel::destroy($supplier_id);
           
            return redirect('/supplier')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            //Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan eror
            return redirect('/supplier')->with('error', 'Data level gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}