<?php



namespace App\Http\Middleware;



use Closure;

use \Illuminate\Support\Facades\Auth;




class IsSale

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

        if (Auth::check() && Auth::user()->vai_tro == 'sale'  && Auth::user()->status==1) {

            return $next($request);

        }

        else{

            abort(404);

        }

    }

}

