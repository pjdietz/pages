<?php

namespace pjdietz\Pages;

class PageParser
{
    public function parse($str)
    {
        $metadata = $this->extractMetadata($str);
        $metadata = $this->decodeMetadata($metadata);
        return (object) [
            "metadata" => $metadata,
            "content" => []
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
}
