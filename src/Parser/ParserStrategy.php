<?php

namespace Converter\Parser;

class ParserStrategy
{
    private const LIKE_ATOM = '<feed xmlns="http://www.w3.org/2005/Atom">';
    private const LIKE_RSS = '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';

    /**
     * @param $raw
     * @return ParserInterface
     * @throws \Exception
     */
    public static function getParserByRaw($raw)
    {
        if (strpos($raw, self::LIKE_ATOM) !== false) {
            return new AtomParser();
        }

        if (strpos($raw, self::LIKE_RSS) !== false) {
            return new RssParser();
        }

        throw new \Exception('undefined format');
    }
}