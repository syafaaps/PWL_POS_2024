<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = BarangModel::all();
        return response()->json($barangs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'required|string|unique:m_barang,barang_kode|max:10',
            'barang_nama' => 'required|string|max:100',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = BarangModel::create($request->all());
        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return response()->json($barang);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'required|string|unique:m_barang,barang_kode,' . $barang->barang_id . '|max:10',
            'barang_nama' => 'required|string|max:100',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang->update($request->all());
        return response()->json($barang, 200);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
