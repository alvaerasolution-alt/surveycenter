<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Transaction;
use App\Models\Layanan;
use App\Models\Setting;


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
    public function boot(): void
    {
        // Map route names → seo slugs stored in settings table
        $seoSlugMap = [
            'landing'    => 'home',
            'home'       => 'home',
            'about'      => 'about',
            'pricing'    => 'pricing',
            'blog.index' => 'blog',
            'contact'    => 'contact',
            'login'      => 'login',
            'register'   => 'register',
        ];

        // SEO View Composer — only for the main layout
        View::composer('layouts.app', function ($view) use ($seoSlugMap) {
            $routeName = Route::currentRouteName() ?? '';
            $slug      = $seoSlugMap[$routeName] ?? '';

            $seoTitle    = null;
            $seoDesc     = null;
            $seoKeywords = null;

            if ($slug) {
                $keys = ["seo_title_{$slug}", "seo_desc_{$slug}", "seo_keywords_{$slug}"];
                $rows = Setting::whereIn('key', $keys)->get()->keyBy('key');

                $seoTitle    = $rows["seo_title_{$slug}"]->value    ?? null;
                $seoDesc     = $rows["seo_desc_{$slug}"]->value     ?? null;
                $seoKeywords = $rows["seo_keywords_{$slug}"]->value ?? null;
            }

            $view->with(compact('seoTitle', 'seoDesc', 'seoKeywords'));
        });

        // General View Composer — shared data for all views
        View::composer('*', function ($view) {
            $jenis    = Layanan::where('category', 'jenis')->get();
            $tambahan = Layanan::where('category', 'tambahan')->get();

            $cartItemCount = 0;
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $cartItemCount = $user->transactions()->where('status', 'pending')->count();
            }

            $view->with(compact('jenis', 'tambahan', 'cartItemCount'));
        });
    }
}
