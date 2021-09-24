<?php

namespace App\Providers;

use App\Models\BlogSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $blog_settings = BlogSettings::find(1);
        View::share('blog_settings', $blog_settings);
    }
}
