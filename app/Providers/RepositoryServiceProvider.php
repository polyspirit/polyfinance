<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\RepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach (glob(app_path() . '/Repositories/*.php') as $filename) {
            $slashParts = explode('/', $filename);
            $dotParts = explode('.', $slashParts[count($slashParts) - 1]);
            $this->app->bind(
                RepositoryInterface::class,
                'App\Repositories\\' . $dotParts[0]
            );
        }
    }

    public function boot(): void
    {
        //
    }
}
