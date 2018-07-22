<?php

namespace Converter\Tests;

use Converter\Converter;
use InvalidArgumentException;
use League\Flysystem\FilesystemInterface;

class ConverterTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testWithoutPath()
    {
        /**
         * @var Converter $converter
         */
        $converter = $this->container->get(Converter::class);

        $this->expectException(InvalidArgumentException::class);

        $converter->convert();
    }

    /**
     * @dataProvider feedProvider
     *
     * @throws \League\Flysystem\FileExistsException
     */
    public function testConvert($inputFeed, $outFeed, $out)
    {
        /**
         * @var Converter $converter
         * @var FilesystemInterface $filesystem
         */
        $filesystem = $this->container->get(FilesystemInterface::class);
        $converter = $this->container->get(Converter::class);

        $path = '/test.xml';
        $filesystem->write($path, $inputFeed);

        $this->assertEquals($outFeed, $converter->convert([
            'path' => $path,
            'out' => $out
        ]));
    }

    public function feedProvider()
    {
        $rss = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
  <channel>
    <title>Новые уроки на Хекслете</title>
    <description>Практические уроки по программированию</description>
    <link>https://ru.hexlet.io/</link>
    <item>
      <title>Pipeline challenge / Главные испытания</title>
      <guid>150</guid>
      <link>https://ru.hexlet.io/courses/main/lessons/pipeline/theory_unit</link>
      <description>Цель: Написать клиент, реализующий передачу сообщений в условиях канала передачи с помехами.</description>
      <pubDate>Wed, 21 Jan 2015 08:59:51 +0000</pubDate>
    </item>
  </channel>
</rss>

FEED;

        $atom = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Новые уроки на Хекслете</title>
  <subtitle>Практические уроки по программированию</subtitle>
  <link>https://ru.hexlet.io/</link>
  <entry>
    <title>Pipeline challenge / Главные испытания</title>
    <id>150</id>
    <link>https://ru.hexlet.io/courses/main/lessons/pipeline/theory_unit</link>
    <summary>Цель: Написать клиент, реализующий передачу сообщений в условиях канала передачи с помехами.</summary>
    <updated>Wed, 21 Jan 2015 08:59:51 +0000</updated>
  </entry>
</feed>

FEED;

        return [
            [$rss, $rss, 'rss'],
            [$rss, $atom, 'atom'],
            [$atom, $atom, 'atom'],
            [$atom, $rss, 'rss']
        ];
    }
}
