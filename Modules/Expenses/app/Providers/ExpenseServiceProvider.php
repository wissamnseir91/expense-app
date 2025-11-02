<?php

namespace Modules\Expenses\app\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Expenses\app\Repositories\Contracts\ExpenseRepositoryInterface;
use Modules\Expenses\app\Repositories\ExpenseRepository;
use Modules\Expenses\app\Events\ExpenseCreated;
use Modules\Expenses\app\Listeners\LogExpenseNotification;

class ExpenseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the repository interface to its implementation
        $this->app->bind(
            ExpenseRepositoryInterface::class,
            ExpenseRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // Register event listeners
        Event::listen(
            ExpenseCreated::class,
            LogExpenseNotification::class
        );
    }
}