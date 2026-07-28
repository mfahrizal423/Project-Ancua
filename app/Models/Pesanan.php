<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'id_kasir', 'nomor_pesanan', 'nama_pelanggan', 'total_harga', 'pajak', 'total_keseluruhan', 'status', 'metode_pembayaran'
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }
}
