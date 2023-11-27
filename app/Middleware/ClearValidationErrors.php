<?php

namespace App\Middleware;

use App\Session\SessionStore;
use App\Views\View;

class ClearValidationErrors
{
    protected $session;

    public function __construct(SessionStore $session)
    {
        $this->session = $session;
    }

    public function __invoke($request, $response, callable $next)
    {
        $next = $next($request, $response);

        $this->session->clear('errors', 'old');

        return $next;
    }
}
