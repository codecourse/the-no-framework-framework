<?php

namespace App\Providers;

use App\Auth\Hashing\BcryptHasher;
use App\Auth\Hashing\Hasher;
use League\Container\ServiceProvider\AbstractServiceProvider;

class HashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Hasher::class
    ];

    public function register()
    {
        $this->getContainer()->share(Hasher::class, function () {
            return new BcryptHasher();
        });
    }
}
