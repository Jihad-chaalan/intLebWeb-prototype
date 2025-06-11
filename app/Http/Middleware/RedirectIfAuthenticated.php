<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Allow access to /register and /login even when authenticated
                if ($request->is('register') || $request->is('login')) {
                    return $next($request);
                }

                $user = Auth::guard($guard)->user();

                if ($user->role === 'admin') {
                    return redirect()->route('dashboard');
                } elseif ($user->role === 'company') {
                    return redirect()->route('company');
                } elseif ($user->role === 'int-seeker') {
                    return redirect()->route('seeker.profile');
                }

                return redirect('/'); // fallback
            }
        }

        return $next($request);
    }
}
