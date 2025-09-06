<?php

namespace App\Models;

use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeFilter(Builder $builder, QueryFilterInterface $filter): Builder
    {
        return $filter->apply($builder);
    }
}
