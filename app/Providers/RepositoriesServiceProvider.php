<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $interfacePath = app_path('Repositories/Interfaces');
        $namespace = 'App\\Repositories\\Interfaces\\';
        foreach (glob("{$interfacePath}/*RepositoryInterface.php") as $filePath) {
            $interface = $namespace . pathinfo($filePath, PATHINFO_FILENAME);
            $domain = str_replace('RepositoryInterface', '', class_basename($interface));
            $factoryClass = "App\\Repositories\\Factories\\{$domain}RepositoryFactory";

            $this->app->scoped($interface, function ($app) use ($factoryClass, $interface) {
                if (!class_exists($factoryClass)) {
                    $msg = "Repository factory not found for [{$interface}]. "
                        . "Expected factory class: {$factoryClass}";

                    if (config('app.debug')) {
                        Log::error($msg);
                    }
                    throw new \RuntimeException($msg);
                }

                $factory = $app->make($factoryClass);
                if (!method_exists($factory, 'make')) {
                    throw new \RuntimeException("Factory {$factoryClass} must have a make() method.");
                }
                return $factory->make();
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
