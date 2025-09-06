<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold Repository, Interface, and Factory classes under app/Repositories';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        //
        $name = $this->argument('name');
        $baseClassName = Str::studly($name);

        $repoDir = app_path('Repositories');
        $interfaceDir = $repoDir . '/Interfaces';
        $factoryDir = $repoDir . '/Factories';

        foreach ([$repoDir, $interfaceDir, $factoryDir] as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // Interface
        $interfacePath = "{$interfaceDir}/{$baseClassName}Interface.php";
        if (! file_exists($interfacePath)) {
            $ifaceStub = <<<PHP
            <?php

            namespace App\Repositories\Interfaces;

            interface {$baseClassName}Interface
            {
                //
            }

            PHP;
            file_put_contents($interfacePath, $ifaceStub);
            $this->components->info("Interface [app/Repositories/Interfaces/{$baseClassName}Interface.php] created successfully.");
        }

        // Repository
        $repoPath = "$repoDir/{$baseClassName}.php";
        if (! file_exists($repoPath)) {
            $repoStub = <<<PHP
             <?php
 
             namespace App\Repositories;
 
             use App\Repositories\Interfaces\\{$baseClassName}Interface;
 
             class {$baseClassName} implements {$baseClassName}Interface
             {
                 //
             }
 
             PHP;
            file_put_contents($repoPath, $repoStub);
            $this->components->info("Repository [app/Repositories/{$baseClassName}.php] created successfully.");
        }

        // Factory
        $factoryPath = "$factoryDir/{$baseClassName}Factory.php";
        if (! file_exists($factoryPath)) {
            $factStub = <<<PHP
             <?php
 
             namespace App\Repositories\Factories;
 
             use App\Repositories\\{$baseClassName};
             use App\Repositories\Interfaces\\{$baseClassName}Interface;
 
             class {$baseClassName}Factory
             {
                 /**
                  * Create and return an instance of {$baseClassName}.
                  *
                  * @return {$baseClassName}Interface
                  */
                 public function make(): {$baseClassName}Interface
                 {
                     return new {$baseClassName}();
                 }
             }
 
             PHP;
            file_put_contents($factoryPath, $factStub);
            $this->components->info("Factory [app/Repositories/Factories/{$baseClassName}Factory.php] created successfully.");
        }

        return self::SUCCESS;
    }
}
