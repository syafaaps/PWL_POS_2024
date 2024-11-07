<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SalesModel extends Model
{
    use HasFactory;
    protected $table = 't_penjualan'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'penjualan_id'; 
    protected $fillable = ['penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal', 'image'];
    
    // public function details(): BelongsTo
    // {
    //     return $this->belongsTo(DetailSalesModel::class, 'detail_id', 'detail_id');
    // } 

    public function details()
    {
        return $this->hasMany(DetailSalesModel::class, 'penjualan_id', 'penjualan_id');
    }
    
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image),
        );
    }
}