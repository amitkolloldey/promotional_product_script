<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
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
        if (Auth::check()) {
            if (!in_array(Auth::user()->getRoleNames()[0], ['admin', 'customer'], true)) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        }else{
            return redirect('order/authenticate');
        }
    }
}
