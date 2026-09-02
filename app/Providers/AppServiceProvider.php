<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Observers\ProjectImageObserver;
use App\Observers\ProjectObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Project::observe(ProjectObserver::class);
        ProjectImage::observe(ProjectImageObserver::class);
    }
}
