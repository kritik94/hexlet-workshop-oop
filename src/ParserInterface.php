<?php

namespace App;

interface ParserInterface
{
    public function parse(string $raw) : array;
}
