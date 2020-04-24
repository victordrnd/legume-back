<?php

namespace App\Providers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\OrderResource;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
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
        Carbon::setLocale('fr');
        OrderResource::withoutWrapping();
        BookingResource::withoutWrapping();
    }
}
