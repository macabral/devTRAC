<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Projects extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'projects_users', 'users_id', 'projects_id')->withPivot('gp', 'relator', 'dev');;
    }

}
