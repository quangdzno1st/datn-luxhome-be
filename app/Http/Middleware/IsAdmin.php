<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use \Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->type == User::ADMIN)  ) {
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}
