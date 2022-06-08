<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\App;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        if(!App::runningInConsole()){ // se não estiver rodando pelo terminal console
            
            foreach($this->permissions_user() as $key => $permission){
                Gate::define($permission->slug, function (User $user) use ($permission) {
                    return $user->hasPermissions($permission->users);
                });
            }
        }
    }

    public function permissions_user()
    {
        return Permission::with('users')->get();
    }
}
