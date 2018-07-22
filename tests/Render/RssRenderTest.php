<?php

namespace Converter\Tests\Render;

use Converter\Tests\TestCase;

class RssRenderTest extends TestCase
{
    /**
     * @dataProvider rssProvider
     */
    public function testRender($feed, $atom)
    {
        $render = new \Converter\Render\RssRender();

        $this->assertEquals($atom, $render->render($feed));
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

        $rss = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
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

        $expect = [
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'items' => [
                [
                    'title' => $itemTitle,
                    'id' => $itemId,
                    'link' => $itemLink,
                    'description' => $itemDescription,
                    'created' => $itemCreated
                ],
                [
                    'title' => $anotherItemTitle,
                    'id' => $anotherItemId,
                    'link' => $anotherItemLink,
                    'description' => $anotherItemDescription,
                    'created' => $anotherItemCreated
                ]
            ]
        ];

        return [
            [$expect, $rss]
        ];
    }
}
