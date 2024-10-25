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
use Illuminate\Support\Facades\Log; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function export_excel()
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->orderBy('stok_tanggal')->get();
        // Load Excel library
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set headers for Excel sheet
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Nama Supplier');
        $sheet->setCellValue('E1', 'User');
        $sheet->setCellValue('F1', 'Tanggal Stok');
        $sheet->setCellValue('G1', 'Jumlah Stok');
        // Bold the headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        // Populate the sheet with data
        $no = 1;
        $row = 2;
        foreach ($stok as $stock) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $stock->barang->barang_kode);
            $sheet->setCellValue('C' . $row, $stock->barang->barang_nama);
            $sheet->setCellValue('D' . $row, $stock->supplier->supplier_nama);
            $sheet->setCellValue('E' . $row, $stock->user->nama);
            $sheet->setCellValue('F' . $row, $stock->stok_tanggal);
            $sheet->setCellValue('G' . $row, $stock->stok_jumlah);
            $row++;
            $no++;
        }
        // Auto size the columns
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data Stok');
        // Create Excel file and prompt download
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        return view('stok.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Failed',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_stok'); // Get file from request
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Skip header row
                        $insert[] = [
                            'supplier_id' => SupplierModel::where('supplier_nama', $value['C'])->first()->supplier_id,
                            'barang_id' => BarangModel::where('barang_nama', $value['B'])->first()->barang_id,
                            'user_id' => auth()->user()->user_id, // Assuming the logged-in user is adding the stock
                            'stok_tanggal' => $value['F'],
                            'stok_jumlah' => $value['G'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    StokModel::insertOrIgnore($insert); // Insert into the stock table
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data successfully imported'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data to import'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_pdf()
    {
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with('supplier')
            ->with('barang')
            ->with('user')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
