<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HeadAndStaffMiddleware
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
        $allowedRoles = ['headmaster', 'staff'];

        if (!auth()->check() || !in_array(auth()->user()->role, $allowedRoles)) {
            if (auth()->check()) {
                if (auth()->user()->role === 'admin' || auth()->user()->role === 'student') {
                    abort(404, 'Page not found');
                }
            } 

            return redirect()->route('login.form');
        }
        
        return $next($request);
    }
}
