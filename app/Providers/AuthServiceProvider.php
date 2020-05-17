<?php

namespace App\Providers;

use App\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->loadGates();
        //
    }


    public function loadGates(){
        Gate::define('user', function($user){
            return $user->role->level >= Role::where('slug',Role::USER)->first()->level;
        });

        Gate::define('operator', function($user){
            return $user->role->level >= Role::where('slug',Role::OPERATOR)->first()->level;
        });

        Gate::define('administrator', function($user){
            return $user->role->level >= Role::where('slug',Role::ADMIN)->first()->level;
        });

       
    }
}
