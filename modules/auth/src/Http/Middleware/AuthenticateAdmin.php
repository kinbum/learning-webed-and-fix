<?php namespace App\Module\Auth\Http\Middleware;

use \Closure;

class AuthenticateAdmin
{
    const LOGIN_ROUTE_NAME_GET = 'auth.login.get';
    const LOGIN_ROUTE_NAME_POST = 'auth.login.post';
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
        $currentRouteName = $request->route()->getName();
        if ( $currentRouteName === $this::LOGIN_ROUTE_NAME_GET || $currentRouteName === $this::LOGIN_ROUTE_NAME_POST ) {
            return $next($request);
        }

        if ( is_in_dashboard() ) {
            if ( auth('web-admin')->guest() ) {
                if ( $request->ajax() || $request->wantsJson() ) {
                    return response('Unauthorized.', \Constants::UNAUTHORIZED_CODE);
                }
                return redirect()->guest(route($this::LOGIN_ROUTE_NAME_GET));
            }
        }

        return $next($request);
    }
}
