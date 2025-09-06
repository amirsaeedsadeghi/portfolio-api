<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service class inside app/Services';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        //
        $name = $this->argument('name');
        $className = Str::studly($name);

        $directory = app_path('Services');
        $path = $directory . "/{$className}.php";

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        if (file_exists($path)) {
            $this->error("Service {$className} already exists!");
            return self::FAILURE;
        }

        $stub = <<<PHP
        <?php

        namespace App\Services;

        class {$className}
        {
            //
        }

        PHP;

        file_put_contents($path, $stub);

        $this->components->info("Service [app/Services/{$className}.php] {$className} created successfully.");

        return self::SUCCESS;
    }
}
