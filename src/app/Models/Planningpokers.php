<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planningpokers extends Model
{
    use HasFactory;

    protected $fillable = [
        'tickets_id',
        'users_id',
        'stoypoint',
        'valorsp'
    ];

}
