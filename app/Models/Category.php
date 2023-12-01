<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Nama tabel yang digunakan oleh model
    protected $table = 'categories';

    // Nama kolom sebagai primary key
    protected $primaryKey = 'id';

    // Tipe data dari primary key
    protected $keyType = 'integer';

    // Menentukan apakah primary key bertipe incrementing
    public $incrementing = true;

    // Menentukan apakah model menggunakan timestamp
    public $timestamps = true;

    // Kolom yang dapat diisi secara massal (fillable)
    protected $fillable = ['name', 'description'];

    // Relasi: Satu kategori dimiliki oleh satu pengguna
    public function user(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'user_id', 'id');
    }

    // Relasi: Satu kategori memiliki banyak buku
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id', 'id');
    }
}
