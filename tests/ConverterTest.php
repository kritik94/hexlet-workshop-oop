<?php

namespace Converter\Tests;

use Converter\Converter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use League\Flysystem\FilesystemInterface;
use DI;

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
     */
    public function testConvertFromPath($inputXml, $outXml, $format)
    {
        /**
         * @var Converter $converter
         * @var FilesystemInterface $filesystem
         */
        $filesystem = $this->container->get(FilesystemInterface::class);
        $converter = $this->container->get(Converter::class);

        $path = '/test.xml';
        $filesystem->write($path, $inputXml);

        $this->assertXmlStringEqualsXmlString($outXml, $converter->convert([
            'path' => $path,
            'out' => $format
        ]));
    }

    /**
     * @dataProvider feedProvider
     */
    public function testConvertFromHttp($inputXml, $outXml, $format)
    {
        $this->container->set(ClientInterface::class, DI\factory(function () use ($inputXml) {
            $mockHandler = new MockHandler([
                new Response(200, [], $inputXml)
            ]);

            return new Client([
                'handler' => $mockHandler
            ]);
        }));

        /**
         * @var Converter $converter
         */
        $converter = $this->container->get(Converter::class);

        $httpPath = 'https://example.local/feed.rss';

        $this->assertXmlStringEqualsXmlString($outXml, $converter->convert([
            'path' => $httpPath,
            'out' => $format
        ]));
    }

    /**
     * @dataProvider limitProvider
     */
    public function testLimit($inputXml, $outXml, $limit)
    {
        /**
         * @var Converter $converter
         * @var FilesystemInterface $filesystem
         */
        $filesystem = $this->container->get(FilesystemInterface::class);
        $converter = $this->container->get(Converter::class);

        $path = '/test.xml';
        $filesystem->write($path, $inputXml);

        $this->assertXmlStringEqualsXmlString($outXml, $converter->convert([
            'path' => $path,
            'out' => 'rss',
            'limit' => $limit
        ]));
    }

    public function limitProvider()
    {
        $rss = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
  <channel>
    <title>title</title>
    <description>description</description>
    <link>https://example.local/</link>
    <item>
      <title>item title 1</title>
      <guid>1</guid>
      <link>https://example.local/1</link>
      <description>item description 1</description>
      <pubDate>Sat, 01 Jan 2000 12:00:00 +0000</pubDate>
    </item>
    <item>
      <title>item title 2</title>
      <guid>2</guid>
      <link>https://example.local/2</link>
      <description>item description 2</description>
      <pubDate>Tue, 04 Jan 2000 12:00:00 +0000</pubDate>
    </item>
    <item>
      <title>item title 3</title>
      <guid>3</guid>
      <link>https://example.local/3</link>
      <description>item description 3</description>
      <pubDate>Mon, 03 Jan 2000 12:00:00 +0000</pubDate>
    </item>
    <item>
      <title>item title 4</title>
      <guid>4</guid>
      <link>https://example.local/4</link>
      <description>item description 4</description>
      <pubDate>Thu, 06 Jan 2000 09:00:00 +0000</pubDate>
    </item>
  </channel>
</rss>
FEED;
        $rssLimit = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
  <channel>
    <title>title</title>
    <description>description</description>
    <link>https://example.local/</link>
    <item>
      <title>item title 1</title>
      <guid>1</guid>
      <link>https://example.local/1</link>
      <description>item description 1</description>
      <pubDate>Sat, 01 Jan 2000 12:00:00 +0000</pubDate>
    </item>
    <item>
      <title>item title 2</title>
      <guid>2</guid>
      <link>https://example.local/2</link>
      <description>item description 2</description>
      <pubDate>Tue, 04 Jan 2000 12:00:00 +0000</pubDate>
    </item>
  </channel>
</rss>
FEED;


        return [
            [$rss, $rss, 0],
            [$rss, $rssLimit, 2],
            [$rss, $rss, 4]
        ];
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
      <pubDate>Sat, 01 Jan 2000 12:00:00 +0000</pubDate>
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
    <published>2000-01-01T12:00:00Z</published>
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
