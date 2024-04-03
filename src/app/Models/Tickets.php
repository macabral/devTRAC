<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'releases_id',
        'relator_id',
        'resp_id',
        'projects_id',
        'types_id',
        'file',
        'docs',
        'prioridade',
        'storypoint'
    ];

}
