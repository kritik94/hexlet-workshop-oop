<?php

namespace App;

class AtomParser implements ParserInterface
{
    public function parse(string $raw): array
    {
        return ['raw' => $raw];
    }
}
