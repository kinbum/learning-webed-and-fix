<?php namespace App\Module\Base\Http\Middleware;

use \Closure;

class LoggedAdmin
{
    public $attributes;
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
        $loggedInUser = $request->user();
        view()->share([
            'loggedInUser' => $loggedInUser
        ]);
        \DashboardMenu::setUser($loggedInUser);
        $request->attributes->add(['loggedInUser' => $loggedInUser]);
        return $next($request);
    }
}
