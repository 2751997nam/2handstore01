<?php

namespace App\Providers;

use App\Option;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');
        Schema::defaultStringLength(191);

        $site_info = Option::getOptions();
        View::share('site_info', $site_info);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            '\App\Repositories\RepositoryInterface',
            '\App\Repositories\EloquentRepository',
            '\App\Repositories\UserRepository',
            '\App\Repositories\MessageRepository'
        );
    }
}
