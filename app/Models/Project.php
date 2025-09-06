<?php

namespace App\Models;

use App\Http\Filters\QueryFilterInterface;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    use Sluggable;
    protected $guarded = [];
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function stacks(): BelongsToMany
    {
        return $this->belongsToMany(Stack::class, 'project_stack');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function scopeFilter(Builder $builder, QueryFilterInterface $filters)
    {
        return $filters->apply($builder);
    }
}
