<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokModel extends Model
{
    use HasFactory;
    protected $table = 't_stok'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'stok_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['barang_id', 'user_id', 'supplier_id', 'stok_tanggal', 'stok_jumlah'];

    // Relasi ke Supplier
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }

    // Relasi ke Barang
    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id');
    }

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}

