<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    protected $table = 'consumption';

    protected $fillable = [
        'name',
        'price',
    ];
}
