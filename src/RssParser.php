<?php

namespace App;

class RssParser implements ParserInterface
{
    public function parse(string $raw): array
    {
        return ['raw' => $raw];
    }
}
