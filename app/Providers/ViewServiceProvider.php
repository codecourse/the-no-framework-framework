<?php

namespace App\Providers;

use App\Views\Extensions\PathExtension;
use App\Views\View;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(View::class, function () use ($config, $container) {
            $loader = new Twig_Loader_Filesystem(base_path('views'));

            $twig = new Twig_Environment($loader, [
                'cache' => $config->get('cache.views.path'),
                'debug' => $config->get('app.debug')
            ]);

            if ($config->get('app.debug')) {
                $twig->addExtension(new Twig_Extension_Debug);
            }

            $twig->addExtension(new PathExtension($container->get(RouteCollection::class)));

            return new View($twig);
        });
    }
}
