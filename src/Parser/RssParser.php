<?php

namespace Converter\Parser;

use Carbon\Carbon;
use SimpleXMLElement;

class RssParser implements ParserInterface
{
    public function parse($raw)
    {
        $xml = new SimpleXMLElement($raw);

        $items = [];
        foreach ($xml->channel->item as $item) {
            $items[] = [
                'title' => (string) $item->title,
                'id' => (string) $item->guid,
                'link' => (string) $item->link,
                'description' => (string) $item->description,
                'created' => Carbon::parse((string) $item->pubDate)
            ];
        }

        return [
            'title' => (string) $xml->channel->title,
            'description' => (string) $xml->channel->description,
            'link' => (string) $xml->channel->link,
            'items' => $items
        ];
    }
}