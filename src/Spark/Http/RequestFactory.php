<?php namespace ITC\Spark\Http;

use Illuminate\Http\Request;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($uri, $method='GET', $params=[], $cookies=[], $files=[], $server=[], $body=null)
    {
        $server = (array) config('spark.server_parameters');
        return Request::create($uri, $method, $params, $cookies, $files, $server, $body);
    }
}
