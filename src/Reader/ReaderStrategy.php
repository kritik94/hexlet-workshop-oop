<?php

namespace Converter\Reader;

class ReaderStrategy
{
    /**
     * @param stirng $path
     * @param array $opts
     * @return ReaderInterface
     */
    public static function getReaderByPath($path, $opts)
    {
        if (strpos($path, 'http://') !== false || strpos($path, 'https://') !== false) {
            return new HttpReader($opts['httpClient']);
        } else {
            return new FileReader($opts['filesystem']);
        }
    }
}