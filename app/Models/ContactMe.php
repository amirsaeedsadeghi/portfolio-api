<?php

namespace App\Models;

use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMe extends Model
{
    use HasFactory;

    protected $table = "contact_me";
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeFilter(Builder $builder, QueryFilterInterface $filter): Builder
    {
        return $filter->apply($builder);
    }

    /**
     * Determine whether the contact message has been read.
     *
     * @return bool True when the message has been read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
