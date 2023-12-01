<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    // Nama tabel yang digunakan oleh model
    protected $table = 'books';

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
        'title',
        'trailer',
        'publication_year',
        'quantity',
        'author',
        'publisher',
        'shell_code'
    ];

    // Relasi: Satu buku dimiliki oleh satu pengguna
    public function user(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'user_id', 'id');
    }

    // Relasi: Satu buku dimiliki oleh satu kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'user_id', 'id');
    }
}
