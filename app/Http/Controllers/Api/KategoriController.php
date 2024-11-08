<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriModel::all();
        return response()->json($kategoris);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode|max:10',
            'kategori_nama' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = KategoriModel::create($request->all());
        return response()->json($kategori, 201);
    }

    public function show(KategoriModel $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriModel $kategori)
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode,' . $kategori->id . '|max:10',
            'kategori_nama' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori->update($request->all());
        return response()->json($kategori, 200);
    }

    public function destroy(KategoriModel $kategori)
    {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
