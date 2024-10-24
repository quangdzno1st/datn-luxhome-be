<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckPermissions
{
    protected $auth;
    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (! is_array($permissions)) {
            $permissions = explode("|", $permissions);
        }


        if ($this->auth->guest() || ! $request->user()->hasPermission($permissions)) {
         return  redirect()->route('admin.403');
//            abort(403, "Không có quyền truy cập.");
        }

        return $next($request);
    }
}
