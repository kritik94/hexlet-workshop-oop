<?php

namespace Converter;

use Converter\Reader\ReaderStrategy;
use GuzzleHttp\Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class Converter
{
    public function __construct($opts = [])
    {
        $this->httpClient = $opts['httpClient'] ?? new Client();
        $this->filesystem = $opts['filesystem'] ?? new Filesystem(new Local(getcwd()));
    }

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

        $feed = $parser($raw);

        return $render->render($feed);
    }
}