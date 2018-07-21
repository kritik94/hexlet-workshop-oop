<?php

namespace App;

class Feed
{
    public $raw;

    public function __construct($raw)
    {
        $this->raw = $raw;
    }

    public function out()
    {
        return $this->raw;
    }
}