<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        $modulesList = Module::all();
        if ($modulesList->count() > 0) {
            foreach ($modulesList as $module) {
                Gate::define($module->name, function (User $user) use ($module) {
                    {
                        if ($user->type == User::ADMIN) {
                            return true;
                        }

                        $roleJson = $user?->group?->permissions ?? [];
                        $roleArr = json_decode($roleJson, true);
                        return isRole($roleArr, $module->name);
                    }
                });
            }
        }
    }
}
