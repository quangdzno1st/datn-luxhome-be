<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */


    public function handle(Request $request, Closure $next,...$type): Response
    {
        if (Auth::check()) {

            if (Auth::user()->type != User::CUSTOMER) {
                return $next($request);
            }
            return redirect()->back();
        }

        return redirect()->route('admin.auth.login')->withErrors([
            'error' => 'Bạn không có quyền truy cập vào trang này.'
        ]);
    }
}
