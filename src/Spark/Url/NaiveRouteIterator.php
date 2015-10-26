<?php namespace ITC\Spark\Url;

use Iterator;

class NaiveRouteIterator extends RouteIterator 
{
    /**
     * @param Iterator $routes
     * @return array
     */
    protected function resolveUrls(Iterator $routes)
    {
        $urls = [];

        foreach ($routes as $route)
        {
            $urls[] = $route->uri();
        }

        return $urls;
    }
}
