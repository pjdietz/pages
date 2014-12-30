<?php

namespace pjdietz\Pages\Test\Unit\PageReader;

use DomainException;
use pjdietz\Pages\ContentProcessor;
use pjdietz\Pages\ProcessorInterface;
use pjdietz\Pages\Test\TestCases\TestCase;

class PageProcessorTest extends TestCase
{
    public function testProcessesContentsWithCallables()
    {
        $contents = [
            "main" => "Main\n",
            "side" => "Side\n"
        ];

        $expected = [
            "main" => "<p>Main</p>",
            "side" => "<p>Side</p>"
        ];

        $processor = new ContentProcessor([
                function ($subject) {
                    return trim($subject);
                },
                function ($subject) {
                    return "<p>$subject</p>";
                }
            ]);

        $processor->process($contents);
        $this->assertEquals($expected, $contents);
    }

    public function testProcessesContentsWithProcessors()
    {
        $contents = [
            "main" => "Main\n",
            "side" => "Side\n"
        ];

        $expected = [
            "main" => "<p>Main</p>",
            "side" => "<p>Side</p>"
        ];

        $processor = new ContentProcessor([
                new PageProcessorTestTrimProcessor(),
                new PageProcessorTestParagramProcessor()
            ]);

        $processor->process($contents);
        $this->assertEquals($expected, $contents);
    }

    /**
     * @expectedException DomainException
     */
    public function testFailsOnInvalidProcessor()
    {
        $contents = [
            "main" => "Main\n",
            "side" => "Side\n"
        ];

        $processor = new ContentProcessor([
                "cat",
                [],
                (object) []
            ]);

        $processor->process($contents);
    }
}

class PageProcessorTestTrimProcessor implements ProcessorInterface
{
    public function process($subject, $metadata = null, $configuration = null)
    {
        return trim($subject);
    }
}

class PageProcessorTestParagramProcessor implements ProcessorInterface
{
    public function process($subject, $metadata = null, $configuration = null)
    {
        return "<p>$subject</p>";
    }
}
