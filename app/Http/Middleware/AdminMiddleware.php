<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Contracts\Auth\Guard;

class AdminMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
     protected $auth;
     
    /**
    * Create a new filter instance.
    *
    * @param  Guard  $auth
    * @return void
    */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        if ($request->User()->isAdmin != "1") {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}