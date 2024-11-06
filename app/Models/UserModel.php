<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; // implementasi class Authenticatable
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'nama',
        'password',
        'level_id',
        'avatar'//tambahan
    ];
        
    //protected $fillable = ['username', 'password', 'nama', 'level_id', 'created_at', 'avatar', 'updated_at'];

    //protected $hidden = ['password']; // jangan di tampilkan saat select

    //protected $casts = ['password' => 'hashed']; // casting password agar otomatis di hash

    /**
    * Relasi ke tabel level
    */
    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id',
    'level_id');
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($avatar) => url('/storage/posts/' . $avatar),
        );
    }
    // public function level(): BelongsTo
    // {
    //     return $this->belongsTo(LevelModel :: class, 'level_id', 'level_id');
    // }

    // public function profil(): HasOne
    // {
    //     return $this->hasOne(ProfilUserModel::class, 'user_id', 'user_id');
    // }

    /**

    * Mendapatkan nama role
    */
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }
    /**
    * Cek apakah user memiliki role tertentu
    */
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->level->level_kode;
    }
    // //JS 4 Prak 1
    // protected $fillable = ['level_id', 'username', 'nama', 'password'];

    // public function level(): BelongsTo
    // {
    //     return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    // }
    // UserModel.php
    public function getJenisKelamin()
    {
        return $this->profil ? $this->profil->jenis_kelamin : null;
    }


}