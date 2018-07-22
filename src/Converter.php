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
        $limit = $args['limit'] ?? 0;

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

        $feed = $this->limit($feed, $limit);

        return $render->render($feed);
    }

    public function limit($feed, $limit)
    {
        if ($limit === 0) {
            return $feed;
        }

        return array_merge($feed, [
            'items' => array_splice($feed['items'], 0, $limit)
        ]);
    }
}