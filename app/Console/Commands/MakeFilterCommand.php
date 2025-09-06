<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeFilterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new QueryFilter class inside app/Http/Filters';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = Str::studly($this->argument('name')); // UserFilter

        $dir = app_path('Http/Filters');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . "/{$name}.php";

        if (file_exists($path)) {
            $this->components->error("Filter [app/Http/Filters/{$name}.php] already exists!");
            return self::FAILURE;
        }

        $stub = <<<PHP
        <?php

        namespace App\Http\Filters;

        /**
         * Class {$name}
         *
         * Applies filtering logic to queries based on request parameters.
         * Extend this class to define sortable, likeFilters, and exactFilters.
         */
        class {$name} extends QueryFilter
        {
            /**
             * Allowed sortable fields.
             *
             * @var array<string,string>
             */
            protected array \$sortable = [
                // 'createdAt' => 'created_at',
                // 'updatedAt' => 'updated_at',
            ];

            /**
             * Allowed filters for LIKE queries.
             *
             * @var string[]
             */
            protected array \$likeFilters = [
                // 'name', 'email'
            ];

            /**
             * Allowed filters for exact match queries.
             *
             * @var string[]
             */
            protected array \$exactFilters = [
                // 'status'
            ];
        }

        PHP;

        file_put_contents($path, $stub);

        $this->components->info("Filter [app/Http/Filters/{$name}.php] created successfully.");

        return self::SUCCESS;
    }
}
