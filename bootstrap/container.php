<?php

$container = new League\Container\Container;

$container->delegate(
    new League\Container\ReflectionContainer
);

$container->addServiceProvider(new App\Providers\ConfigServiceProvider());

foreach ($container->get('config')->get('app.providers') as $provider) {
    $container->addServiceProvider($provider);
}
