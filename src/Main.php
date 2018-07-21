<?php

namespace App;

use DI\ContainerBuilder;
use function DI\create;
use function DI\get;
use GuzzleHttp\ClientInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Main
{
    public static function main()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            'httpClient' => create(\GuzzleHttp\Client::class)
                ->constructor(),
            'filesystem' => create(Filesystem::class)
                ->constructor(get('fileAdapter')),
            'fileAdapter' => create(Local::class)
                ->constructor(getcwd(), 0),
            'atomCompiler' => create(AtomCompiler::class),
            'rssCompiler' => create(RssCompiler::class),
            'atomParser' => create(AtomParser::class),
            'rssParser' => create(RssParser::class)
        ]);

        $container = $containerBuilder->build();

        $app = new \Silly\Application();

        $app->useContainer($container, true, true);

        $app->command('run path [--out=]', function (
            Parser $parser,
            Compiler $compiler,
            ClientInterface $httpClient,
            FilesystemInterface $filesystem,
            $path,
            $out,
            OutputInterface $output
        ) {
            $raw = $filesystem->read($path);

            $feed = $parser->parse($raw);

            $compiled = $compiler->compile($out, $feed);

            $output->writeln($compiled);
        })->defaults([
            'out' => 'rss'
        ])->descriptions('Help convert and manipulate rss or atom feed', [
            '--out' => 'output format'
        ]);

        $app->run();
    }
}
