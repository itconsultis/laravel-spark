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
     * Lifecycle moment
     * @param void
     * @return void
     */
    public function register()
    {
        // bind a concrete GeneratorInterface
        $this->app->bind(OutputGeneratorInterface::class, function($app)
        {
            return new OutputGenerator($app, new RequestFactory());
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
