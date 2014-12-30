<?php

namespace pjdietz\Pages;

class PageParser
{
    public $defaultContentKey = 'main';

    public function parse($subject)
    {
        // Read and remove metadata from the subject.
        $metadata = $this->extractMetadata($subject);

        // Decode the metadata.
        $metadata = $this->decodeMetadata($metadata);

        // Read and remove named content sections from the subject.
        $content = $this->extractContent($subject);

        // Set the default content to the remainder of the subject.
        if (!isset($content[$this->defaultContentKey])) {
            $content[$this->defaultContentKey] = $subject;
        }

        // Return an object containing metadata and content.
        return (object) [
            "metadata" => $metadata,
            "content" => $content
        ];
    }

    /**
     * Retrieve the section representing the metadata.
     *
     * The default implementation extract all content between <!-- @metadata --> and <!-- @metadata end -->
     *
     * @param string $subject The original content to parse.
     * @return string The extracted metadata.
     */
    protected function extractMetadata(&$subject)
    {
        $metadata = "";

        // Match content between <!-- @metadata --> and <!-- @metadata end -->
        $regex = <<<'REGEX'
{
    # Opening marker. <!-- metadata -->
    \<!--\s+metadata\s+--\>

    # Rest of the line.
    .*$\n

    # \3: Content
    (?<content>
        (?>
            (?!\<!--\s+metadata\s+end\s+--\> [ ]* \n) # Not a closing marker.
            .*\n+
        )+
    )

    # Closing marker. <!-- metadata end -->
    \<!--\s+metadata\s+end\s+--\>

}xm
REGEX;

        // Store the content to $metadata.
        // Return "" to replace the entirty of the matched metadata block.
        $callback = function ($matches) use (&$metadata) {
            $metadata = $matches["content"];
            return "";
        };

        // Modify the passed $subject.
        $subject = preg_replace_callback($regex, $callback, $subject, 1);

        // Return the metadata block extracted through the callback.
        return $metadata;
    }

    /**
     * @param string $metadata
     * @return mixed
     */
    protected function decodeMetadata($metadata)
    {
        return json_decode($metadata);
    }

    /**
     * Retrieve the section representing the metadata.
     *
     * The default implementation extract all content between <!-- @metadata --> and <!-- @metadata end -->
     *
     * @param string $subject The original content to parse.
     * @return string The extracted metadata.
     */
    protected function extractContent(&$subject)
    {
        // Build an array to fill with extracted content.
        $content = [];

        // Match content between <!-- section:{name} --> and <!-- section:{name} end -->
        $regex = <<<'REGEX'
{
    # Opening marker. <!-- section:{name} -->
    \<!--\s+section\:(?<name>[a-zA-Z0-9\-_]+)\s+--\>

    # Rest of the line.
    .*$\n

    # \3: Content
    (?<content>
        (?>
            (?!\<!--\s+section\:\1\s+end\s+--\> [ ]* \n) # Not a closing marker.
            .*\n+
        )+
    )

    # Closing marker. <!-- metadata end -->
    \<!--\s+section\:\1\s+end\s+--\>

}xm
REGEX;

        // Store the content to $content[name]
        // Return "" to replace the entirty of the matched metadata block.
        $callback = function ($matches) use (&$content) {
            $content[$matches["name"]] = $matches["content"];
            return "";
        };

        // Modify the passed $subject.
        $subject = preg_replace_callback($regex, $callback, $subject);

        return $content;
    }
}
