<?php namespace ITC\Spark\Output;

use Log;
use Iterator;
use Symfony\Component\HttpKernel\HttpKernelInterface as HttpKernel;
use Illuminate\Contracts\Filesystem\Filesystem as StorageInterface;
use ITC\Spark\Exception as SparkException;
use ITC\Spark\Http\RequestFactoryInterface as RequestFactory;
use Phine\Path\Path;

class Generator implements GeneratorInterface
{
    /**
     * Application instance
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * HTTP request factory instance
     * @var \ITC\Spark\Http\RequestFactoryInterface
     */
    protected $requests;

    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    protected $storage;

    /**
     * @param \Illuminate\Contracts\Http\Kernel $app
     * @param ITC\Spark\Http\RequestFactoryInterface $factory
     */
    public function __construct(HttpKernel $app, RequestFactory $requests)
    {
        $this->app = $app;
        $this->requests = $requests;
    }

    /**
     * Satisfies GeneratorInterface#setStorage
     * @param League\Flysystem\FilesystemInterface $storage
     * @return void
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Satisfies GeneratorInterface#getStorage
     * @param void
     * @return League\Flysystem\FilesystemInterface
     */
    public function getStorage()
    {
        if (!$this->storage)
        {
            throw new SparkException('storage interface is not set'); 
        }

        return $this->storage;
    }

    /**
     * Render all generable routes and write output to the filesystem
     * Satisfies GeneratorInterface#generate
     * @param \Iterator $urls
     * @return void
     */
    public function generate(Iterator $urls)
    {
        $paths = [];

        foreach ($urls as $url)
        {
            $html = $this->render($url);
            $paths[] = $this->store($url, $html);
        }

        return $paths;
    }

    /**
     * Render output (usually HTML) given a named route
     * @param string $url
     * @return string
     */
    protected function render($url)
    {
        $request = $this->requests->create($url);
        $response = $this->app->handle($request, null, false);

        if ($response->getStatusCode() !== 200)
        {
            throw new SparkException('got non-200 HTTP status on url '.$url); 
        }

        return $response->getContent();
    }

    /**
     * Store the output (usually HTML) associated with the given route to
     * file storage.
     * @param string $url
     * @param string $output
     * @return string - the stored file path
     */
    protected function store($url, $output)
    {
        $url = parse_url($url);
        $prefix = empty($url['path']) ? '' : $url['path'];

        // derive the output file path and write to it
        $path = sprintf('%s/index.html', Path::canonical($prefix));
        $this->getStorage()->put($path, $output);

        return $path;
    }

}
