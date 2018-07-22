<?php

namespace Converter\Parser;

class ParserStrategy
{
    private const LIKE_ATOM = '<feed';
    private const LIKE_RSS = '<rss';

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