<?php

namespace App\Controllers;

use App\Auth\Auth;
use App\Cookie\CookieJar;
use App\Views\View;

class HomeController
{
    protected $view;

    public function __construct(View $view, CookieJar $cookie)
    {
        $this->view = $view;
        $this->cookie = $cookie;
    }

    public function index($request, $response)
    {
        $this->cookie->clear('abc');

        return $this->view->render($response, 'home.twig');
    }
}
