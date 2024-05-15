<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logtickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'tickets_id',
        'users_id',
        'origin'
    ];
}
