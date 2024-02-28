<?php

namespace App\Providers;

use App\Contract\TaskRepositoryInterface;
use App\repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class RepoditoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
