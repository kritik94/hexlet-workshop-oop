<?php

namespace Converter;

use DI\ContainerBuilder;
use function DI\create;
use function DI\get;
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
        $containerBuilder->addDefinitions([
            'httpClient' => create(\GuzzleHttp\Client::class)
                ->constructor(),
            'filesystem' => create(Filesystem::class)
                ->constructor(get('fileAdapter')),
            'fileAdapter' => create(Local::class)
                ->constructor(getcwd(), 0),
        ]);

        $container = $containerBuilder->build();

        $app = new \Silly\Application();

        $app->useContainer($container, true, true);

        $app->command('run path [--out=]', function (
            ClientInterface $httpClient,
            FilesystemInterface $filesystem,
            $path,
            $out,
            OutputInterface $output
        ) {
            $converter = new Converter([
                'httpClient' => $httpClient,
                'filesystem' => $filesystem
            ]);

            $result = $converter->convert([
                'path' => $path,
                'out' => $out
            ]);

            $output->writeln($result);
        })->defaults([
            'out' => 'rss'
        ])->descriptions('Help convert and manipulate rss or atom feed', [
            '--out' => 'output format'
        ]);

        return $app;
    }
}
