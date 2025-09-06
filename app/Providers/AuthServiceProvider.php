<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Skill;
use App\Models\Stack;
use App\Models\AboutMe;
use App\Models\ContactMe;
use App\Models\Project;
use App\Models\NavbarItem;
use App\Policies\UserPolicy;
use App\Policies\SkillPolicy;
use App\Policies\StackPolicy;
use App\Policies\AboutMePolicy;
use App\Policies\ContactMePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\NavbarItemPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        User::class => UserPolicy::class,
        AboutMe::class => AboutMePolicy::class,
        NavbarItem::class => NavbarItemPolicy::class,
        Skill::class => SkillPolicy::class,
        Stack::class => StackPolicy::class,
        Project::class => ProjectPolicy::class,
        ContactMe::class => ContactMePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
