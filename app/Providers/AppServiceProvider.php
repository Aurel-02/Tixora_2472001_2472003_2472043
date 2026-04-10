<?php

namespace App\Providers;

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
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $unreadCount = 0;
            if (auth()->check() || session()->has('login_admin.id')) {
                $uid = session('login_admin.id') ?? auth()->id();
                if ($uid) {
                    $unreadCount = \Illuminate\Support\Facades\DB::table('notifikasi')
                        ->where('id_user', $uid)
                        ->where('is_read', 0)
                        ->count();
                }
            }
            $view->with('unreadCount', $unreadCount);
        });
    }
}
