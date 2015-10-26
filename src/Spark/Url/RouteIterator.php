<?php namespace ITC\Spark\Url;

use Iterator;
use ArrayIterator;

abstract class RouteIterator implements IteratorInterface
{
    /**
     * @var Iterator
     */
    private $urls = null;

    /**
     * @var Iterator
     */
    private $routes;

    /**
     * @param Iterator $routes
     * @return array|Iterator
     */
    abstract protected function resolveUrls(Iterator $routes);

    /**
     * @param \Iterator $routes
     */
    public function __construct(Iterator $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param Iterator $routes
     * @param string $method
     * @return ArrayIterator
     */    
    protected function filterRoutes(Iterator $routes, $method='GET')
    {
        $filtered = [];

        foreach ($routes as $route)
        {
            if (in_array($method, $route->methods()))
            {
                $filtered[] = $route;
            }
        }

        return new ArrayIterator($filtered);
    }

    /**
     * Satisfies \ITC\Spark\Url\IteratorInterface#current
     * @param void
     * @return string
     */
    public function current()
    {
        return $this->urls->current();
    }

    /**
     * Satisfies \ITC\Spark\Url\IteratorInterface#key
     * @param void
     * @return string
     */
    public function key()
    {
        return $this->urls->key();
    }

    /**
     * Satisfies \ITC\Spark\Url\IteratorInterface#next
     * @param void
     * @return void
     */
    public function next()
    {
        return $this->urls->next();
    }

    /**
     * Satisfies \ITC\Spark\Url\IteratorInterface#rewind
     * @param void
     * @return void
     */
    public function rewind()
    {
        if ($this->urls === null)
        {
            // we're only really interested in GET routes
            $filtered_routes = $this->filterRoutes($this->routes);

            // create URLs from routes
            $urls = $this->resolveUrls($filtered_routes);

            // hold onto the generated urls
            $this->urls = $urls instanceof Iterator ? $urls : new ArrayIterator($urls);
        }

        return $this->urls->rewind();
    }

    /**
     * Satisfies \ITC\Spark\Url\IteratorInterface#valid
     * @param void
     * @return bool
     */
    public function valid()
    {
        return $this->urls->valid();
    }

}
