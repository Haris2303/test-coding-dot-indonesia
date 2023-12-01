<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    // Nama tabel yang digunakan oleh model
    protected $table = 'users';

    // Nama kolom sebagai primary key
    protected $primaryKey = 'id';

    // Tipe data dari primary key
    protected $keyType = 'integer';

    // Menentukan apakah primary key bertipe incrementing
    public $incrementing = true;

    // Menentukan apakah model menggunakan timestamp
    public $timestamps = true;

    // Kolom yang dapat diisi secara massal (fillable)
    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    // Relasi: Satu pengguna memiliki banyak kategori
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'user_id', 'id');
    }

    // Relasi: Satu pengguna memiliki banyak buku
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'user_id', 'id');
    }

    // Metode untuk mendapatkan nama identifikasi autentikasi
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // Metode untuk mendapatkan nilai identifikasi autentikasi
    public function getAuthIdentifier()
    {
        return $this->username;
    }

    // Metode untuk mendapatkan kata sandi autentikasi
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Metode untuk mendapatkan nilai remember token
    public function getRememberToken()
    {
        return $this->token;
    }

    // Metode untuk menetapkan nilai remember token
    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    // Metode untuk mendapatkan nama kolom remember token
    public function getRememberTokenName()
    {
        return 'token';
    }
}
