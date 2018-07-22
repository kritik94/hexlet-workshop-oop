<?php

namespace Converter\Render;

class RenderStrategy
{
    public const FORMAT_ATOM = 'atom';
    public const FORMAT_RSS = 'rss';

    /**
     * @param $format
     * @return RenderInterface
     */
    public static function getRenderByFormat($format)
    {
        $renderClass = '\\Converter\\Render\\' . ucwords($format) . 'Render';

        return new $renderClass;
    }
}
