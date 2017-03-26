<?php namespace App\Module\Acl\Http\Middleware;

use \Closure;

class HasRole
{
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  array|string $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->hasRole($roles)) {
            abort(\Constants::FORBIDDEN_CODE);
        }

        return $next($request);
    }
}
