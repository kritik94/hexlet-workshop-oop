<?php

namespace Converter\Render;

class RssRender implements RenderInterface
{
    private const XML_HEADER = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
</rss>
XML;

    public function render($feed)
    {
        $xml = new \SimpleXMLElement(self::XML_HEADER);

        $channel = $xml->addChild('channel');

        $channel->addChild('title', $feed['title']);
        $channel->addChild('description', $feed['description']);
        $channel->addChild('link', $feed['link']);

        foreach ($feed['items'] as $item) {
            $xmlItem = $channel->addChild('item');
            $xmlItem->addChild('title', $item['title']);
            $xmlItem->addChild('guid', $item['id']);
            $xmlItem->addChild('link', $item['link']);
            $xmlItem->addChild('description', $item['description']);
            $xmlItem->addChild('pubDate', $item['created']);
        }

        $doc = new \DomDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($xml->asXML());

        return $doc->saveXML();
    }
}