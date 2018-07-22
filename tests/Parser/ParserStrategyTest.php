<?php

use PHPUnit\Framework\TestCase;

class ParserStrategyTest extends TestCase
{
    /**
     * @dataProvider rawProvider
     */
    public function testChooseParserByRaw($raw, $expectedParser)
    {
        $parser = \Converter\Parser\ParserStrategy::getParserByRaw($raw);

        $this->assertEquals($expectedParser, get_class($parser));
    }

    public function rawProvider()
    {
        $atom = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
</feed>
FEED;

        $rss = <<<FEED
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
FEED;

        return [
            [$atom, \Converter\Parser\AtomParser::class],
            [$rss, \Converter\Parser\RssParser::class]
        ];
    }
}
