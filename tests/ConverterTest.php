<?php

use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testWithoutPath()
    {
        $this->expectException(InvalidArgumentException::class);

        $converter = new Converter\Converter();
        $converter->convert();
    }

    /**
     * @dataProvider feedProvider
     */
    public function testConvertRssToRss($inputFeed, $outFeed, $out)
    {
        $path = '/test.xml';
        $filesystem = new \League\Flysystem\Filesystem(
            new League\Flysystem\Memory\MemoryAdapter()
        );

        $filesystem->write($path, $inputFeed);

        $converter = new Converter\Converter([
            'filesystem' => $filesystem
        ]);

        $this->assertEquals($outFeed, $converter->convert([
            'path' => $path,
            'out' => $out
        ]));
    }

    public function feedProvider()
    {
        $rss = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
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
</rss>
FEED;

        return [
            [$rss, $rss, 'rss'],
            [$rss, $atom, 'atom'],
            [$atom, $atom, 'atom'],
            [$atom, $rss, 'rss']
        ];
    }
}
