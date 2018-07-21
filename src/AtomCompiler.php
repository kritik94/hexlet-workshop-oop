<?php

namespace App;

class AtomCompiler implements CompilerInterface
{
    public function compile(Array $feed) : string
    {
        return $feed['raw'];
    }
}
