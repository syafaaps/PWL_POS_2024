<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class StokController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Middleware for authentication
    }

    public function index()
    {
        $activeMenu = 'stok';
        $breadcrumb = (object)[
            'title' => 'Data Stok',
            'list' => [
                ['name' => 'Home', 'url' => url('/dashboard')],
                ['name' => 'Stok', 'url' => url('/stok')],
            ]
        ];
        $stok = StokModel::select('stok_id')->get();
        
        return view('stok.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'stok' => $stok
        ]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::select('stok_id', 'supplier_id','barang_id', 'stok_tanggal', 'stok_jumlah', 'updated_at')
                        ->with(['supplier', 'user', 'barang'])
                        ->get();
        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_stok').'\')" class="btn btn-info btn-sm">Update</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    private function getDropdownData()
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return compact('supplier', 'barang', 'user');
    }

    // public function create_ajax()
    // {
    //     return view('stok.create_ajax', $this->getDropdownData());
    // }

    public function create_ajax()
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create_ajax')
        ->with('supplier', $supplier)
        ->with('barang', $barang)
        ->with('user', $user);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
                'user_id' => ['required','integer', 'exists:m_user,user_id'],
                'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
                'stok_tanggal' => ['required', 'date_format:Y-m-d\TH:i'],
                'stok_jumlah' => ['required', 'numeric'],
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            
            if ($validator->fails()) {
                Log::error('Validasi Gagal:', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            Log::info('Data disimpan:', $request->all());

            
            StokModel::create($request->all());
            
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }
        return redirect('/stok');
    }

    // public function store_ajax(Request $request)
    // {
    //     Log::info('Data diterima di store_ajax:', $request->all()); // Logging data yang diterima

    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
    //             'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
    //             'user_id' => ['required', 'integer', 'exists:m_user,user_id'],
    //             'stok_tanggal' => ['required', 'date_format:Y-m-d\TH:i'],  // format tanggal harus sesuai
    //             'stok_jumlah' => ['required', 'numeric'],
    //         ];

    //         $messages = [
    //             'supplier_id.required' => 'Supplier harus dipilih',
    //             'barang_id.required' => 'Barang harus dipilih',
    //             'user_id.required' => 'Pengguna harus dipilih',
    //             'stok_jumlah.required' => 'Jumlah stok harus diisi',
    //         ];
            
    //         // Lakukan validasi
    //         $validator = Validator::make($request->all(), $rules, $messages);
            
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi Gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }
            
    //         // Proses penyimpanan data stok
    //         try {
    //             $stok = StokModel::create([
    //                 'supplier_id' => $request->input('supplier_id'),
    //                 'barang_id' => $request->input('barang_id'),
    //                 'user_id' => $request->input('user_id'),
    //                 'stok_tanggal' => $request->input('stok_tanggal'),
    //                 'stok_jumlah' => $request->input('stok_jumlah'),
    //             ]);
                
    //             Log::info('Data stok berhasil disimpan:', $stok); // Logging data yang disimpan

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data berhasil disimpan'
    //             ]);
    //         } catch (\Exception $e) {
    //             Log::error('Error saat menyimpan data stok: ' . $e->getMessage()); // Logging error

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
    //             ]);
    //         }
    //     }
    //     return redirect('/stok');  // Jika request bukan AJAX, redirect ke halaman utama
    // }

    public function edit_stok($id)
    {
        $stok = StokModel::with(['barang', 'supplier'])->find($id);
        if (!$stok) {
            return response()->json([
                'status' => false,
                'message' => 'Data stok tidak ditemukan'
            ]);
        }
        return view('stok.edit_stok', array_merge($this->getDropdownData(), ['stok' => $stok]));
    }

    // public function update_stok(Request $request, $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $stok = StokModel::find($id);
    //         if (!$stok) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data tidak ditemukan'
    //             ]);
    //         }
    //         // Gabungkan data yang tidak diubah dengan data yang diinputkan
    //         $request->merge([
    //             'supplier_id' => $stok->supplier_id,
    //             'barang_id' => $stok->barang_id,
    //             'user_id' => $stok->user_id,
    //             'stok_tanggal' => $stok->stok_tanggal,
    //         ]);
    //         $rules = [
    //             'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
    //             'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
    //             'user_id' => ['required', 'integer', 'exists:m_user,user_id'],
    //             'stok_tanggal' => ['required', 'date_format:Y-m-d H:i:s'],
    //             'stok_change' => ['required', 'integer'],
    //             'operation' => ['required', 'in:add,subtract'],
    //         ];
    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi gagal.',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }
    //         // Update stok jumlah berdasarkan operasi
    //         if ($request->operation === 'add') {
    //             $stok->stok_jumlah += $request->stok_change;
    //         } elseif ($request->operation === 'subtract') {
    //             $stok->stok_jumlah -= $request->stok_change;
    //             if ($stok->stok_jumlah < 0) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Jumlah stok tidak boleh kurang dari 0.'
    //                 ]);
    //             }
    //         }
    //         $stok->stok_tanggal = now(); // Update tanggal stok
    //         $stok->save();
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data berhasil diupdate'
    //         ]);
    //     }
    //     return redirect('/stok');
    // }
    public function update_stok(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if (!$stok) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            $rules = [
                'stok_change' => ['required', 'integer'],
                'operation' => ['required', 'in:add,subtract'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Update stok berdasarkan operasi
            if ($request->operation === 'add') {
                $stok->stok_jumlah += $request->stok_change;
            } elseif ($request->operation === 'subtract') {
                $stok->stok_jumlah -= $request->stok_change;
                if ($stok->stok_jumlah < 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Jumlah stok tidak boleh kurang dari 0.'
                    ]);
                }
            }

            $stok->stok_tanggal = now(); // Update tanggal stok
            $stok->save();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        }
        return redirect('/stok');
    }

    public function confirm_ajax($id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            
            if ($stok) {
                $stok->delete();
                Log::info('Data stok berhasil dihapus:', ['stok_id' => $id]); // Logging data yang dihapus

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                Log::warning('Data stok tidak ditemukan untuk dihapus:', ['stok_id' => $id]); // Logging jika stok tidak ditemukan

                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/stok');
    }
}
