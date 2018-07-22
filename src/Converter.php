<?php

namespace Converter;

use Converter\Parser\ParserStrategy;
use Converter\Reader\ReaderStrategy;
use Converter\Render\RenderStrategy;
use GuzzleHttp\ClientInterface;
use League\Flysystem\FilesystemInterface;

class Converter
{
    private $httpClient;
    private $filesystem;

    /**
     * @Inject
     * @param ClientInterface $httpClient
     * @param FilesystemInterface $filesystem
     */
    public function __construct(ClientInterface $httpClient, FilesystemInterface $filesystem)
    {
        $this->httpClient = $httpClient;
        $this->filesystem = $filesystem;
    }

    /**
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public function convert($args = [])
    {
        $path = $args['path'] ?? false;
        $out = $args['out'] ?? false;

        if (!$path) {
            throw new \InvalidArgumentException("path doesn't exists");
        }

        $reader = ReaderStrategy::getReaderByPath($path, [
            'httpClient' => $this->httpClient,
            'filesystem' => $this->filesystem
        ]);

        $raw = $reader->read($path);

        $parser = ParserStrategy::getParserByRaw($raw);
        $render = RenderStrategy::getRenderByFormat($out);

        $feed = $parser->parse($raw);

        return $render->render($feed);
    }
}