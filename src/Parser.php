<?php

namespace App;

class Parser
{
    public const FORMAT_ATOM = 'atom';
    public const FORMAT_RSS = 'rss';

    /**
     * @var ParserInterface
     */
    private $atomParser;
    /**
     * @var ParserInterface
     */
    private $rssParser;

    public function __construct(AtomParser $atomParser, RssParser $rssParser)
    {
        $this->atomParser = $atomParser;
        $this->rssParser = $rssParser;
    }

    /**
     * @param $raw
     * @return array
     */
    public function parse($raw)
    {
        switch ($this->defineFormat($raw)) {
            case self::FORMAT_ATOM:
                return $this->atomParser->parse($raw);
            case self::FORMAT_RSS:
                return $this->rssParser->parse($raw);
        }
    }

    /**
     * @param $raw
     * @return string
     */
    private function defineFormat($raw)
    {
        return self::FORMAT_ATOM;
    }
}
