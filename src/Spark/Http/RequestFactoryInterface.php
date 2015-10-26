<?php namespace ITC\Spark\Http;

interface RequestFactoryInterface
{
    /**
     * @see Symfony\Component\HttpFoundation\Reqest
     * @param string $uri        The URI
     * @param string $method     The HTTP method
     * @param array  $parameters The query (GET) or request (POST) parameters
     * @param array  $cookies    The request cookies ($_COOKIE)
     * @param array  $files      The request files ($_FILES)
     * @param array  $server     The server parameters ($_SERVER)
     * @param string $body       The raw body
     * @return Request A Request instance
     */
    public function create($uri, $method='GET', $parameters=[], $cookies=[], $files=[], $server=[], $body=null);
}
