<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $primaryKey = ['project_id', 'user_id'];
    protected $guarded = [];
    public $incrementing = false;

    protected function setKeysForSaveQuery($query): Builder
    {
        $query
            ->where('project_id', '=', $this->getAttribute('project_id'))
            ->where('user_id', '=', $this->getAttribute('user_id'));

        return $query;
    }
}
