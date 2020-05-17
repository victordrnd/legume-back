<?php

namespace App\Providers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\OrderResource;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Stripe;
use Illuminate\Support\Facades\Validator;

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
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Carbon::setLocale('fr');
        OrderResource::withoutWrapping();
        BookingResource::withoutWrapping();
    }
}
