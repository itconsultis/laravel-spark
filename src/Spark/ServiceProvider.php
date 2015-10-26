<?php namespace ITC\Spark;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ITC\Spark\Output\GeneratorInterface as OutputGeneratorInterface;
use ITC\Spark\Output\Generator as OutputGenerator;
use ITC\Spark\Http\RequestFactory;
use ITC\Spark\Url\IteratorInterface as UrlIteratorInterface;
use ITC\Spark\Url\NaiveRouteIterator;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @param void
     * @return \ITC\Spark\Url\IteratorInterface
     */
    protected function createUrlIterator()
    {
        // override me
        $routes = $this->app->make('router')->getRoutes()->getIterator();
        return new NaiveRouteIterator($routes);
    }

    /**
     * Lifecycle moment
     * @param void
     * @return void
     */
    public function register()
    {
        // bind a concrete UrlIteratorInterface
        $this->app->bind(UrlIteratorInterface::class, function($app)
        {
            return $this->createUrlIterator();
        });

        // bind a concrete GeneratorInterface
        $this->app->bind(OutputGeneratorInterface::class, function($app)
        {
            $urls = $app->make(UrlIteratorInterface::class);
            return new OutputGenerator($app, new RequestFactory(), $urls);
        });
    }

    /**
     * Lifecycle moment
     * @param void
     * @return void
     */
    public function boot()
    {
        $resources = realpath(__DIR__.'/resources');

        $this->publishes([
            "$resources/config/spark.php" => base_path('config'),
        ]);
    }
}
