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
}


