<?php

foreach ($container->get('config')->get('app.middleware') as $middleware) {
    $route->middleware($container->get($middleware));
}
