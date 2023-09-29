<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersProjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'projects_id',
        'gp',
        'relator',
        'dev',
        'tester'
    ];

}
