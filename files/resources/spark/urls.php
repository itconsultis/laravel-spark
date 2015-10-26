<?php

/**
 * @param \ArrayIterator $urls
 * @param \ArrayIterator $routes
 * @param \Illuminate\Contracts\Container\Container $app
 */
return function($urls, $routes, $app)
{
    $urls->append('/cities');
    $urls->append('/cities/san-francisco');
    $urls->append('/cities/new-york');
};

