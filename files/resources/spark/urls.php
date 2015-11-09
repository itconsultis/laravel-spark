<?php

/**
 * @param \ArrayIterator $urls
 * @param \ArrayIterator $routes
 * @param \Illuminate\Contracts\Container\Container $app
 */
return function($urls, $routes, $app)
{
    foreach ($routes as $route)
    {
        $urls->append($route->uri());
    }
};

