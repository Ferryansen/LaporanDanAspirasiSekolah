<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            if (auth()->check() && (auth()->user()->role === 'headmaster' || auth()->user()->role === 'staff' || auth()->user()->role === 'student')) {
                abort(404, 'Page not found');
            }
    
            return redirect()->route('login.form');
        }

        return $next($request);
    }
}