<?php

namespace Converter;

use DI;
use DI\ContainerBuilder;
use GuzzleHttp\ClientInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Output\OutputInterface;


class AppFactory
{
    public static function createCliApp()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $containerBuilder->addDefinitions([
            ClientInterface::class => DI\create(\GuzzleHttp\Client::class),
            FilesystemInterface::class => DI\factory(function () {
                return new Filesystem(new Local(getcwd()));
            }),
            Converter::class => DI\autowire()
        ]);

        $container = $containerBuilder->build();

        $app = new \Silly\Application();

        $app->useContainer($container, true, true);

        $app->command('run path [--out=] [--sort-by=] [--order=] [--limit=]', function (
            $path,
            $out,
            $sortBy,
            $order,
            $limit,
            Converter $converter,
            OutputInterface $output
        ) {
            $result = $converter->convert([
                'path' => $path,
                'out' => $out,
                'sortBy' => $sortBy,
                'order' => $order,
                'limit' => $limit
            ]);

            $output->writeln($result);
        })->defaults([
            'out' => 'rss',
            'sort-by' => false,
            'order' => 'asc',
            'limit' => 0
        ])->descriptions('Help convert and manipulate rss or atom feed', [
            '--out' => 'output format'
        ]);

        return $app;
    }
}
