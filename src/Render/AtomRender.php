<?php

namespace Converter\Render;

class AtomRender implements RenderInterface
{
    private const XML_HEADER = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
</feed>
XML;

    public function render($feed)
    {
        $xml = new \SimpleXMLElement(self::XML_HEADER);

        $xml->addChild('title', $feed['title']);
        $xml->addChild('subtitle', $feed['description']);
        $xml->addChild('link', $feed['link']);

        foreach ($feed['items'] as $item) {
            $xmlItem = $xml->addChild('entry');
            $xmlItem->addChild('title', $item['title']);
            $xmlItem->addChild('id', $item['id']);
            $xmlItem->addChild('link', $item['link']);
            $xmlItem->addChild('summary', $item['description']);
            $xmlItem->addChild('published', $item['created']->toIso8601ZuluString());
        }

        $doc = new \DomDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($xml->asXML());

        return $doc->saveXML();
    }
}