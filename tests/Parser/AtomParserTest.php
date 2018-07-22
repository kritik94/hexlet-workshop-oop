<?php

namespace Converter\Tests\Parser;

use Carbon\Carbon;
use Converter\Tests\TestCase;

class AtomParserTest extends TestCase
{
    /**
     * @dataProvider atomProvider
     */
    public function testParse($expect, $atom)
    {
        $parser = new \Converter\Parser\AtomParser();

        $this->assertEquals($expect, $parser->parse($atom));
    }

    public function atomProvider()
    {
        $title = 'title';
        $description = 'description';
        $link = 'https://example.local';
        $itemTitle = 'i title';
        $itemId = '1';
        $itemLink = 'https://example.local/1';
        $itemDescription = 'i description';
        $itemCreated = '2001-01-01T12:00:00Z';

        $anotherItemTitle = 'another title';
        $anotherItemId = '2';
        $anotherItemLink = 'https://example.local/2';
        $anotherItemDescription = 'another description';
        $anotherItemCreated = '2002-02-01T13:00:00Z';

        $atom = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$title}</title>
  <subtitle>{$description}</subtitle>
  <link>{$link}</link>
  <entry>
    <title>{$itemTitle}</title>
    <id>{$itemId}</id>
    <link>{$itemLink}</link>
    <summary>{$itemDescription}</summary>
    <published>{$itemCreated}</published>
  </entry>
  <entry>
    <title>{$anotherItemTitle}</title>
    <id>{$anotherItemId}</id>
    <link>{$anotherItemLink}</link>
    <summary>{$anotherItemDescription}</summary>
    <published>{$anotherItemCreated}</published>
  </entry>
</feed>
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
            [$expect, $atom]
        ];
    }
}
