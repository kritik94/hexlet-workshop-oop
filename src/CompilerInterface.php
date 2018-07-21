<?php

namespace App;

interface CompilerInterface
{
    public function compile(Array $feed) : string;
}
