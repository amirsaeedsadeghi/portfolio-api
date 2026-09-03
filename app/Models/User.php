<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\AssetTypeEnum;
use App\Enums\UserRoleEnum;
use App\Http\Filters\QueryFilterInterface;
use App\Support\AssetUrl;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasAvatar
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRoleEnum::class,
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed (usually int|string)
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     */

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Mutator for the "password" attribute.
     *
     * Automatically hashes the password using bcrypt before storing it in the database.
     * This ensures that plain-text passwords are never saved.
     *
     * @param string|null $value The plain-text password to be hashed. If null, no action is taken.
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        if ($value !== null) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Determine if the user has administrative privileges.
     *
     * Checks whether the user's role is set to 'admin' based on the UserRoleEnum.
     *
     * @return bool True if the user is an admin; false otherwise.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::ADMIN;
    }

    public function scopeFilter(Builder $builder, QueryFilterInterface $filter): Builder
    {
        return $filter->apply($builder);
    }

    /**
     * Get the avatar URL used by Filament.
     *
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return AssetUrl::url($this->image, AssetTypeEnum::AVATAR);
    }
}
