<?php

namespace App;

class Compiler
{
    public const FORMAT_ATOM = 'atom';
    public const FORMAT_RSS = 'rss';

    /**
     * @var CompilerInterface
     */
    private $atomCompiler;
    /**
     * @var CompilerInterface
     */
    private $rssCompiler;

    public function __construct(AtomCompiler $atomCompiler, RssCompiler $rssCompiler)
    {
        $this->atomCompiler = $atomCompiler;
        $this->rssCompiler = $rssCompiler;
    }

    public function compile($format, Array $feed) : string
    {
        switch ($format) {
            case self::FORMAT_ATOM:
                return $this->atomCompiler->compile($feed);
            case self::FORMAT_RSS:
                return $this->rssCompiler->compile($feed);
        }
    }
}
