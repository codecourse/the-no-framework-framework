<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Session\Flash;
use App\Views\View;
use League\Route\RouteCollection;

class LoginController extends Controller
{
    protected $view;

    protected $auth;

    protected $route;

    protected $flash;

    public function __construct(
        View $view,
        Auth $auth,
        RouteCollection $route,
        Flash $flash
    ) {
        $this->view = $view;
        $this->auth = $auth;
        $this->route = $route;
        $this->flash = $flash;
    }

    public function index($request, $response)
    {
        return $this->view->render($response, 'auth/login.twig');
    }

    public function signin($request, $response)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $attempt = $this->auth->attempt($data['email'], $data['password'], isset($data['remember']));

        if (!$attempt) {
            $this->flash->now('error', 'Could not sign you in with those details.');

            return redirect($request->getUri()->getPath());
        }

        return redirect($this->route->getNamedRoute('home')->getPath());
    }
}
