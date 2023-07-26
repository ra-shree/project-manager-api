<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ProjectMember extends Model
{
    use HasFactory;

    protected $primaryKey = ['project_id', 'user_id'];
    protected $fillable = [
        'user_id',
        'project_id',
    ];
    public $incrementing = false;
    protected $guarded = [];

}
