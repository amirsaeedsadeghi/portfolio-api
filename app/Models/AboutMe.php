<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutMe extends Model
{
    use HasFactory;

    protected $table = "about_me";
    protected $guarded = [];

    protected $casts = [
        'language' => 'array',
        'availability' => 'boolean',
        'years_of_experience' => 'integer',
    ];
}
