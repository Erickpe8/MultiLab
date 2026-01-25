<?php

namespace Tests\Traits\Database;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait RefreshDatabaseSkipDropForeign
{
    use RefreshDatabase {
        refreshInMemoryDatabase as baseRefreshInMemoryDatabase;
        migrateUsing as baseMigrateUsing;
    }

    /**
     * {@inheritdoc}
     */
    protected function refreshInMemoryDatabase(): void
    {
        if (! $this->usingInMemoryDatabase()) {
            $this->baseRefreshInMemoryDatabase();

            return;
        }

        $this->runMigrationsSkipping($this->skipProblematicMigrations());

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Run the migrations while skipping the problematic ones.
     *
     * @param  array<int, string>  $excluded
     * @return void
     */
    protected function runMigrationsSkipping(array $excluded): void
    {
        $migrationFiles = collect(glob(database_path('migrations/*.php')))
            ->sort()
            ->map(fn (string $path) => str_replace('\\', '/', substr($path, strlen(base_path()) + 1)));

        foreach ($migrationFiles as $relativePath) {
            if (in_array(basename($relativePath), $excluded, true)) {
                continue;
            }

            $this->artisan('migrate', array_merge(
                $this->migrationCommandParameters(),
                ['--path' => $relativePath]
            ));
        }

        if ($this->shouldSeed()) {
            $this->artisan('db:seed', ['--class' => $this->seeder()]);
        }
    }

    /**
     * List of migrations that SQLite cannot run.
     *
     * @return array<int, string>
     */
    protected function skipProblematicMigrations(): array
    {
        return [
            '2026_01_23_084900_make_issued_by_nullable_on_loans.php',
            '2026_01_23_085200_convert_loans_status_to_varchar.php',
        ];
    }

    /**
     * Additional parameters when running migrate per file.
     *
     * @return array<string, mixed>
     */
    protected function migrationCommandParameters(): array
    {
        $params = $this->baseMigrateUsing();
        $params['--seed'] = false;
        $params['--seeder'] = null;

        return $params;
    }
}
