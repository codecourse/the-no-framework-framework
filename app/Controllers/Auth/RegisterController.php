<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\Hasher;
use App\Controllers\Controller;
use App\Models\User;
use App\Session\Flash;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use League\Route\RouteCollection;

class RegisterController extends Controller
{
    protected $view;

    protected $hash;

    protected $route;

    protected $db;

    protected $auth;

    public function __construct(
        View $view,
        Hasher $hash,
        RouteCollection $route,
        EntityManager $db,
        Auth $auth
    ) {
        $this->view = $view;
        $this->hash = $hash;
        $this->route = $route;
        $this->db = $db;
        $this->auth = $auth;
    }

    public function index($request, $response)
    {
        return $this->view->render($response, 'auth/register.twig');
    }

    public function register($request, $response)
    {
        $data = $this->validateRegistration($request);

        $user = $this->createUser($data);

        if (!$this->auth->attempt($data['email'], $data['password'])) {
            return redirect('/');
        }

        return redirect($this->route->getNamedRoute('home')->getPath());
    }

    protected function createUser($data)
    {
        $user = new User;

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hash->create($data['password'])
        ]);

        $this->db->persist($user);
        $this->db->flush();

        return $user;
    }

    protected function validateRegistration($request)
    {
        return $this->validate($request, [
            'email' => ['required', 'email', ['exists', User::class]],
            'name' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);
    }
}
