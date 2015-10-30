<?php namespace ITC\Spark\Console;

use Iterator;
use ArrayIterator;
use UnexpectedValueException;
use Illuminate\Console\Command;
use ITC\Spark\Exception as SparkException;
use ITC\Spark\Output\GeneratorInterface as OutputGenerator;

class GenerateCommand extends Command
{
    protected $signature = 'spark:generate {--storage=local} {--root=http://localhost} {--lang=}';

    protected $description = 'Generate static pages.';

    /**
     * Execute the command
     * @param void
     * @return void
     */
    public function handle()
    {
        $app = $this->laravel;

        $this->setLanguage($app);
        $this->setUrlRoot($app);

        $generator = $app->make(OutputGenerator::class);
        $generator->setStorage($this->createStorage($app));

        $urls = $this->createUrls($app);
        $generated_paths = $generator->generate($urls);

        $this->info('Generated:');

        foreach ($generated_paths as $path)
        {
            $this->comment("  $path");
        }
    }

    /**
     * @param \Illuminate\Foundation\Container $app
     * @return void
     */
    private function createStorage($app)
    {
        return $app->make('filesystem')->disk($this->option('storage'));
    }

    /**
     * Include the configured PHP script path. We are expecting the script to return
     * a callable thing that modifies the $urls by reference.
     * @param \Illuminate\Foundation\Container $app
     * @param \ArrayIterator $urls
     * @return void
     * @throws \UnexpectedValueException
     */
    private function createUrls($app)
    {
        // complain if spark.urls is not configured
        if (!$filepath = realpath(config('spark.urls')))
        {
            throw new SparkException('file not found at '.config('spark.urls'));
        }

        $enumerate = require $filepath;

        // complain if the url enumerator is not callable
        if (!is_callable($enumerate))
        {
            throw new SparkException('PHP script at '.$filepath.' does not return a callable');
        }

        // invoke the enumerator with the url list, router and service container 
        $urls = new ArrayIterator();
        $routes = $app->make('router')->getRoutes()->getIterator();
        $enumerate($urls, $routes, $app);

        return $urls;
    }

    /**
     * Assign the language for translations
     * @param void
     * @return void
     */
    private function setLanguage($app)
    {
        if ($lang = $this->option('lang'))
        {
            $app->make('config')->set('app.locale', $lang);
        }
    }

    /**
     * Assign a base url to the UrlGenerator
     * @param void
     * @return void
     */
    private function setUrlRoot($app)
    {
        if ($urlroot = $this->option('root'))
        {
            $app->make('url')->forceRootUrl($this->option('root'));
        }
    }
}
