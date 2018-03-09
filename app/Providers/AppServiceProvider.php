<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/*
  To see how the AppServiceProvider is constructed, see config/app.php, 'providers'.

  Notice this:

  App\Providers\AppServiceProvider::class,
*/

class AppServiceProvider extends ServiceProvider
{
    /*
      If we weren't doing "boot" below, and only "register,ing", we could do
      this:

      protected $defer = true;

      The defer mechanism tells laravel that this class isn't necessarily
      needed on every page load (ie. demand loading only), only loaded when
      its requested. That said, the presence of boot means defer can't be used.
    */
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
        /*
        \App::singleton('App\Billing\Stripe' function($app) {
          // See the config/services.php file, stripe, secret
          return new \App\Billing\Stripe('services.stripe.secret');
        });
        */
    }
}
