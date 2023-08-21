<?php

namespace Cap\Commercio\Helper;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;

class MarkdownHelper
{
    /**
     * @throws CommonMarkException
     */
    public function markdownToHtml(string $body): string
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        // remove indentation
        if ($white = substr($body, 0, strspn($body, " \t\r\n\0\x0B"))) {
            $body = preg_replace("{^$white}m", '', $body);
        }

        return $converter->convert($body);
    }

    /**
     * @throws CommonMarkException
     */
    public function htmlToMarkdown(string $text): string
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'hard_break' => true,
            'strip_tags' => true,
            'remove_nodes' => 'head style',
        ]);

        return $converter->convert($text);
    }


}