<?php

namespace App;

class RssCompiler implements CompilerInterface
{
    public function compile(Array $feed) : string
    {
        return $feed['raw'];
    }
}
