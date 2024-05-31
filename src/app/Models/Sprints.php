<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprints extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'description',
        'projects_id',
        'status',
        'start',
        'end',
        'lessons'
    ];
}
