<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirportName extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'airports_name';

    protected $fillable = [
        'code',
        'name',
        'language'
    ];
}
