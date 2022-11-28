<?php

namespace App\Http\Middleware;

use App\MyApplication\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class adminOrguest
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
        $user = auth("user")->user();
        if (is_null($user))
            return $next($request);
        if ($user->role===Role::Admin->value)
            return $next($request);
        throw new AccessDeniedHttpException("");
    }
}
