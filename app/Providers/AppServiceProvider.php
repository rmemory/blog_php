<?php

namespace App\Providers;

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
        // This is a view composer
        //
        // Fire this function (or could be a class) whenever
        // the layouts.sidebar view is loaded, which enables
        // us to load the archives in one place
        // This binds the 'archives' to the layouts.sidebar view
        // so that that information will be available wherever
        // layouts.sidebar is used without users of layouts.sidebar
        // needing to be modified themselves to pull in the 'archives'.
        view()->composer('layouts.sidebar', function ($view) {
          $view->with('archives', \App\Post::archives());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
