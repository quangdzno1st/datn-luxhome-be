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
                Gate::define("view_{$module->name}", function (User $user) use ($module) {
                    return $this->checkPermission($user, 'view', $module);
                });

                // Quyền tạo module
                Gate::define("create_{$module->name}", function (User $user) use ($module) {
                    return $this->checkPermission($user, 'create', $module);
                });

                // Quyền cập nhật module
                Gate::define("edit_{$module->name}", function (User $user) use ($module) {
                    return $this->checkPermission($user, 'edit', $module);
                });

                // Quyền xóa module
                Gate::define("delete_{$module->name}", function (User $user) use ($module) {
                    return $this->checkPermission($user, 'delete', $module);
                });
            }
        }
    }
    protected function checkPermission(User $user, $action, Module $module)
    {
        // Kiểm tra nếu là admin
//        if ($user->type == User::ADMIN) {
//            return true;
//        }

        $roleJson = $user?->group?->permissions ?? [];
        $roleArr = json_decode($roleJson, true);
        // Kiểm tra quyền theo module và hành động
        return in_array("{$action}_{$module->name}", $roleArr);
    }
}
