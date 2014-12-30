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

    public function testExtractsContent()
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
            "main" => "Main\n",
            "side" => "Side\n"
        ];

        $parser = new PageParser();
        $page = $parser->parse($str);
        $this->assertEquals($content, $page->content);
    }
}


