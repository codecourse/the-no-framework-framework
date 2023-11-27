<?php

namespace App\Middleware;

use App\Exceptions\CsrfTokenException;
use App\Security\Csrf;

class CsrfGuard
{
    protected $csrf;

    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
    }

    public function __invoke($request, $response, callable $next)
    {
        if (!$this->requestRequiresProtection($request)) {
            return $next($request, $response);
        }

        if (!$this->csrf->tokenIsValid($this->getTokenFromRequest($request))) {
            throw new CsrfTokenException();
        }

        return $next($request, $response);
    }

    protected function getTokenFromRequest($request)
    {
        return $request->getParsedBody()[$this->csrf->key()] ?? null;
    }

    protected function requestRequiresProtection($request)
    {
        return in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH']);
    }
}
