<?php

namespace Converter\Tests;

use Converter\Converter;
use DI;
use DI\Container;
use DI\ContainerBuilder;
use GuzzleHttp\ClientInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Memory\MemoryAdapter;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $containerBuilder->addDefinitions([
            ClientInterface::class => DI\create(\GuzzleHttp\Client::class),
            FilesystemInterface::class => DI\factory(function () {
                return new Filesystem(new MemoryAdapter());
            }),
            Converter::class => DI\autowire()
        ]);

        $container = $containerBuilder->build();
        $this->container = $container;

        parent::setUp();
    }
}