<?php

namespace Converter\Tests\Parser;

use Carbon\Carbon;
use Converter\Tests\TestCase;

class RssParserTest extends TestCase
{
    /**
     * @dataProvider rssProvider
     */
    public function testParse($feed, $xml)
    {
        $parser = new \Converter\Parser\RssParser();

        $this->assertEquals($feed, $parser->parse($xml));
    }

    public function rssProvider()
    {
        $title = 'title';
        $description = 'description';
        $link = 'https://example.local';
        $itemTitle = 'i title';
        $itemId = '1';
        $itemLink = 'https://example.local/1';
        $itemDescription = 'i description';
        $itemCreated = 'Wed, 1 Jan 2000 12:00:00 +0000';

        $anotherItemTitle = 'another title';
        $anotherItemId = '2';
        $anotherItemLink = 'https://example.local/2';
        $anotherItemDescription = 'another description';
        $anotherItemCreated = 'Wed, 1 Jan 2010 13:00:00 +0000';

        $xml = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>{$title}</title>
    <description>{$description}</description>
    <link>{$link}</link>
    <item>
      <title>{$itemTitle}</title>
      <guid>{$itemId}</guid>
      <link>{$itemLink}</link>
      <description>{$itemDescription}</description>
      <pubDate>{$itemCreated}</pubDate>
    </item>
    <item>
      <title>{$anotherItemTitle}</title>
      <guid>{$anotherItemId}</guid>
      <link>{$anotherItemLink}</link>
      <description>{$anotherItemDescription}</description>
      <pubDate>{$anotherItemCreated}</pubDate>
    </item>
  </channel>
</rss>
FEED;

        $feed = [
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'items' => [
                [
                    'title' => $itemTitle,
                    'id' => $itemId,
                    'link' => $itemLink,
                    'description' => $itemDescription,
                    'created' => Carbon::parse($itemCreated)
                ],
                [
                    'title' => $anotherItemTitle,
                    'id' => $anotherItemId,
                    'link' => $anotherItemLink,
                    'description' => $anotherItemDescription,
                    'created' => Carbon::parse($anotherItemCreated)
                ]
            ]
        ];

        return [
            [$feed, $xml]
        ];
    }
}
