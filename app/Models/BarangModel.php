<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;
    protected $table = 'm_barang'; // Nama tabel barang
    protected $primaryKey = 'barang_id';

    protected $fillable = ['barang_kode', 'barang_nama', 'kategori_id'];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}

