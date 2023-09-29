<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Releases extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'description',
        'projects_id',
        'status'
    ];
}
