<?php

namespace App\Middleware;

use App\Auth\Auth;

class Authenticated
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke($request, $response, callable $next)
    {
        if (!$this->auth->check()) {
            $response = redirect('/');
        }

        return $next($request, $response);
    }
}
