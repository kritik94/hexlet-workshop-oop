<?php

use Feed\Feed;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testWithoutPath()
    {
        $this->expectException(InvalidArgumentException::class);

        $converter = new Converter\Converter();
        $converter->convert();
    }

    public function testCreateAndOutFeed()
    {
        $raw = <<<RSS
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Новые уроки на Хекслете</title>
    <description>Практические уроки по программированию</description>
    <link>https://ru.hexlet.io/</link>
    <webMaster>info@hexlet.io</webMaster>
    <item>
      <title>Pipeline challenge / Главные испытания</title>
      <guid isPermaLink="false">150</guid>
      <link>https://ru.hexlet.io/courses/main/lessons/pipeline/theory_unit</link>
      <description>Цель: Написать клиент, реализующий передачу сообщений в условиях канала передачи с помехами.</description>
      <pubDate>Wed, 21 Jan 2015 08:59:51 +0000</pubDate>
    </item>
  </channel>
</rss>
RSS;
        $path = '/test.rss';
        $filesystem = new \League\Flysystem\Filesystem(
            new League\Flysystem\Memory\MemoryAdapter()
        );

        $filesystem->write($path, $raw);

        $converter = new Converter\Converter([
            'filesystem' => $filesystem
        ]);

        $this->assertEquals($raw, $converter->convert([
            'path' => $path
        ]));
    }
}