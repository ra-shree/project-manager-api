<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['project_id', 'user_id'];

    protected $fillable = [
        'project_id',
        'user_id',
    ];

    public $incrementing = false;
}
