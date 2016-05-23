<?php

namespace MezzoLabs\Mezzo\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;

class Authenticate extends MezzoMiddleware
{
    protected $key = "mezzo.auth";

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var PermissionGuard
     */
    protected $permissionGuard;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @param PermissionGuard $permissionGuard
     */
    public function __construct(Guard $auth, PermissionGuard $permissionGuard)
    {
        $this->auth = $auth;
        $this->permissionGuard = $permissionGuard;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('mezzo/auth/login');
            }
        }
        if (!$this->permissionGuard->allowsCockpit())
            return response('Unauthorized. You are not allow to view the cockpit.', 401);

        return $next($request);
    }
}
