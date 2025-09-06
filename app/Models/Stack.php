<?php

namespace App\Models;

use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stack extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_stack');
    }

    public function scopeFilter(Builder $builder, QueryFilterInterface $filters)
    {
        return $filters->apply($builder);
    }
}
