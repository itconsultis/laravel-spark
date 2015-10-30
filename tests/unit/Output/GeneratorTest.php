<?php namespace ITC\Spark\Test;

use Mockery;
use ArrayIterator;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Illuminate\Contracts\Filesystem\Filesystem as StorageInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ITC\Spark\Http\RequestFactoryInterface;
use ITC\Spark\Urls\IteratorInterface as UrlIteratorInterface;
use ITC\Spark\Output\GeneratorInterface;
use ITC\Spark\Output\Generator;

class GeneratorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        // fake symfony http kernel
        $this->kernel = Mockery::mock(HttpKernelInterface::class);

        // fake request factory
        $this->requests = Mockery::mock(RequestFactoryInterface::class);

        // url iterator
        $this->urls = new ArrayIterator(['http://foo/bar/baz']);

        // fake storage interface
        $this->storage = Mockery::mock(StorageInterface::class);

        $this->generator = new Generator($this->kernel, $this->requests);
        $this->generator->setStorage($this->storage); 
    }

    public function test_interface_compliance()
    {
        $this->assertTrue($this->generator instanceof GeneratorInterface);
    }

    public function test_storage_access()
    {
        $storage1 = Mockery::mock(StorageInterface::class);
        $this->generator->setStorage($storage1);
        $storage2 = $this->generator->getStorage();

        $this->assertSame($storage1, $storage2);
    }

    public function test_passes_if_storage_interface_receives_put_call_with_expected_args()
    {
        $expected_output_path = '/bar/baz/index.html';
        $html = '<h1>Hello world</h1>';

        $request = Mockery::mock(Request::class);
        $this->requests->shouldReceive('create')->withArgs(['http://foo/bar/baz'])->andReturn($request);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getContent')->andReturn($html);

        $this->kernel->shouldReceive('handle')->withArgs([$request, Mockery::any(), Mockery::any()])->andReturn($response);
        $this->storage->shouldReceive('put')->withArgs([$expected_output_path, $html]);

        $this->generator->generate($this->urls);
    }

}
