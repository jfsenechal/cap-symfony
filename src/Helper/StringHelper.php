<?php

namespace Cap\Commercio\Helper;

class StringHelper
{
    public static function parse(string $text): string
    {
        return nl2br($text);
    }

}