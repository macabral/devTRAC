<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'datadoc',
        'tipodocs_id',
        'projects_id',
        'users_id',
        'file'
    ];
}
