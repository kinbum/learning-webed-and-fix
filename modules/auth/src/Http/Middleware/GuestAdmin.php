<?php namespace App\Module\Auth\Http\Middleware;

use \Closure;

class GuestAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  array|string $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        if (auth('web-admin')->check()) {
            return redirect()->to(route('dashboard.index.get'));
        }

        return $next($request);
    }
}
