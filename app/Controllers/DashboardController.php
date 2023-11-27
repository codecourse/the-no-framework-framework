<?php

namespace App\Controllers;

use App\Auth\Auth;
use App\Views\View;

class DashboardController
{
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index($request, $response)
    {
        return $this->view->render($response, 'dashboard/index.twig');
    }
}
