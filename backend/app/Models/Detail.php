<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'tID',
        'iID',
        'quantity',
        'subtotal',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'iID', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaksi::class, 'tID', 'id');
    }
}
