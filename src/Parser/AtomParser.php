<?php

namespace Converter\Parser;

use Carbon\Carbon;
use SimpleXMLElement;

class AtomParser implements ParserInterface
{
    public function parse($raw)
    {
        $xml = new SimpleXMLElement($raw);

        $items = [];
        foreach ($xml->entry as $item) {
            $items[] = [
                'title' => (string) $item->title,
                'id' => (string) $item->id,
                'link' => (string) $item->link,
                'description' => (string) $item->summary,
                'created' => Carbon::parse((string) $item->published)
            ];
        }

        return [
            'title' => (string) $xml->title,
            'description' => (string) $xml->subtitle,
            'link' => (string) $xml->link,
            'items' => $items
        ];
    }
}