<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
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
        if (!auth()->check() || auth()->user()->role !== 'staff') {
            if (auth()->check() && (auth()->user()->role === 'headmaster' || auth()->user()->role === 'student' || auth()->user()->role === 'admin')) {
                abort(404, 'Page not found');
            } 

            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
