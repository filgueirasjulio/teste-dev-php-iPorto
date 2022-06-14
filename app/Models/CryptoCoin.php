<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCoin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['symbol', 'price', 'time'];
}
