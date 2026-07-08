<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        if ($this->app->environment('production')) {
            $url->forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            $view->with(
                'navFamilies',
                Category::topLevel()->with('children')->orderBy('title')->get()
            );

            $view->with(
                'socialLinks',
                SocialLink::where('is_active', true)->orderBy('sort_order')->get()
            );

            $view->with('siteSettings', SiteSetting::current());
        });
    }
}
