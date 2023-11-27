<?php

namespace App\Providers;

use App\Session\Session;
use App\Session\SessionStore;
use League\Container\ServiceProvider\AbstractServiceProvider;

class SessionServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        SessionStore::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(SessionStore::class, function () {
            return new Session();
        });
    }
}
