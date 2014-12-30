<?php

namespace pjdietz\Pages\Test\Unit\PageReader;

use pjdietz\Pages\PageParser;
use pjdietz\Pages\Test\TestCases\TestCase;

class PageReaderTest extends TestCase
{
    public function testExtractsMetadata()
    {
        $metadata = <<<JSON
{
    "title": "Page Title"
}
JSON;

        $str = <<<STR
<!-- metadata -->
{$metadata}
<!-- metadata end -->

Content
STR;

        $parser = new PageParser();
        $page = $parser->parse($str);
        $this->assertEquals(json_decode($metadata), $page->metadata);
    }

    public function testExtractsNamedContent()
    {
        $str = <<<STR
<!-- metadata -->
{
    "title": "Page Title"
}
<!-- metadata end -->

<!-- section:main -->
Main
<!-- section:main end -->

<!-- section:side -->
Side
<!-- section:side end -->

STR;

        $content = [
            "main" => "Main",
            "side" => "Side"
        ];

        $parser = new PageParser();
        $page = $parser->parse($str);

        // Remove white space from each content.
        array_walk($page->content, function (&$value) {
                $value = trim($value);
            });

        $this->assertEquals($content, $page->content);
    }

    public function testExtractsRemainingContent()
    {
        $str = <<<STR
<!-- metadata -->
{
    "title": "Page Title"
}
<!-- metadata end -->

<!-- section:left -->
Left
<!-- section:left end -->

<!-- section:right -->
Right
<!-- section:right end -->

Main

STR;

        $content = [
            "main" => "Main",
            "left" => "Left",
            "right" => "Right",
        ];

        $parser = new PageParser();
        $page = $parser->parse($str);

        // Remove white space from each content.
        array_walk($page->content, function (&$value) {
                $value = trim($value);
            });

        $this->assertEquals($content, $page->content);
    }
}


