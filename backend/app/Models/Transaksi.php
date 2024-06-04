<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'pID',
        'total',
        'transaction_date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pID', 'id');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'tID');
    }
}
