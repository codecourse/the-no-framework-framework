<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;

class LogoutController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function logout($request, $response)
    {
        $this->auth->logout();

        return redirect('/');
    }
}
