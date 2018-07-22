<?php

namespace Converter\Reader;

use GuzzleHttp\ClientInterface;

class HttpReader implements ReaderInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function read($path)
    {
        $response = $this->httpClient->request('get', $path);

        return $response->getBody();
    }
}