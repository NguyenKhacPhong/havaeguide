<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        app(Gate::class)->before(function(Authorizable $auth, $route){
            if(method_exists($auth, 'hasPermission')){
                return $auth->hasPermission($route) ? $auth->hasPermission($route) : false;
            }

            return false; 
        });
        //
    }
}
