<?php

namespace pjdietz\Pages\Test\Unit\PageReader;

use pjdietz\Pages\PageParser;
use pjdietz\Pages\Test\TestCases\TestCase;

class PageReaderTest extends TestCase
{
    public function testParsesMetadata()
    {
        $parser = new PageParser();
        $page = $parser->parse("");
        $this->assertNotNull($page);
    }
}


