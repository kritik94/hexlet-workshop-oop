<?php

namespace Converter\Parser;

class ParserStrategy
{
    private const LIKE_ATOM = '';
    private const LIKE_RSS = '';

    /**
     * @param $raw
     * @return ParserInterface
     */
    public function getParserByRaw($raw)
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