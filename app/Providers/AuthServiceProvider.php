<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\File;
use App\Models\Group;
use App\Models\User;
use App\MyApplication\Role;
use App\Policies\FilePolicy;
use App\Policies\GroupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        File::class => FilePolicy::class,
        Group::class => GroupPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
//        Gate::define("isAdmin",fn() => auth()->user()->role===Role::Admin->value);
    }
}
