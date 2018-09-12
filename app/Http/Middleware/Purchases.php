<?php

namespace App\Http\Middleware;

use Closure;

class Purchases
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
        if ($request->user()->hasRole('postpaid')) {
            flash()->error('This action is not authorized.');
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
