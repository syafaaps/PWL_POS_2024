<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesModel;


class SalesController extends Controller
{
    public function index()
    {
        return SalesModel::all();
    }

    // public function store(Request $request)
    // {
    //     $user = SalesModel::create($request->all());
    //     return response()->json($user, 201);
    // }

    public function store(Request $request)
    {
        // // validasi data yang diinput user
        $request->validate([
            'user_id'           => 'required',
            'pembeli'           => 'required',
            'penjualan_kode'    => 'required',
            'penjualan_tanggal' => 'required',
            'image'             => 'required|image|mimes:jpeg,png,jpg,giv,svg|max:2048',
        ]);
        // // Buat objek baru dari model penjualan dan simpan data ke database
        $penjualan = SalesModel::create([
            'user_id'           => $request->user_id,
            'pembeli'           => $request->pembeli,
            'penjualan_kode'    => $request->penjualan_kode,
            'penjualan_tanggal' => $request->penjualan_tanggal,
            'image'             => $request->image->hashName(),
        ]);
        // Kembalikan respons JSON jika pengguna berhasil dibuat
        if ($penjualan) {
            return response()->json([
                'success' => true,
                'message' => 'penjualan berhasil ditambahkan',
                'data' => $penjualan,
            ], 201);
        }
        // Kembalikan respons JSON jika proses pembuatan pengguna gagal
        return response()->json([
            'success' => false,
            'message' => 'Gagal menambah penjualan',
        ], 409);
    }

    public function show($id)
    {
       $penjualan = SalesModel::find($id);
       if (is_null($penjualan)) {
           return response()->json(['message' => 'No data found for the provided id'],  404);
       }else{
           return response()->json(['data'=>$penjualan]);
        // return PenjualanModel::find($id);
       }
    }
    public function update(Request $request, $id)
    {
        $penjualan = SalesModel::findOrFail($id);
        $penjualan->update($request->all());
        return response()->json([
           'message' => 'Successfully updated penjualan!',
           'data' =>  $penjualan
        ]);
    }
    public function destroy($id){
        try {
            $penjualan = SalesModel::findOrFail($id);
            $penjualan -> delete();
          return response()->json([
              "message"=>"Record deleted successfully!"
          ]);
       } catch (\Exception $e) {
           return response()->json(['message'=> 'Failed to delete record'],400);
       }
    }
}