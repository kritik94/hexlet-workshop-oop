<?php

use PHPUnit\Framework\TestCase;

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
        $itemCreated = 'Wed, 1 Jan 2000 12:00:00 +0000';

        $anotherItemTitle = 'another title';
        $anotherItemId = '2';
        $anotherItemLink = 'https://example.local/2';
        $anotherItemDescription = 'another description';
        $anotherItemCreated = 'Wed, 1 Jan 2010 13:00:00 +0000';

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
        <updated>{$itemCreated}</updated>
    </entry>
    <entry>
        <title>{$anotherItemTitle}</title>
        <id>{$anotherItemId}</id>
        <link>{$anotherItemLink}</link>
        <summary>{$anotherItemDescription}</summary>
        <updated>{$anotherItemCreated}</updated>
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
            [$expect, $atom]
        ];
    }
}
