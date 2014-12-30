<?php

namespace pjdietz\Pages;

use DomainException;

class ContentProcessor
{
    private $processsors;

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(array $processors)
    {
        $this->processsors = $processors;
    }

    public function process(array &$contents, $metadata = null, $configuration = null)
    {
        $processors = $this->processsors;

        // Build a callable suitable for array_walk that calls each $processor.
        $callable = function (&$content) use ($processors, $metadata, $configuration) {
            foreach ($processors as $index => $processor) {
                if (is_callable($processor)) {
                    $content = $processor($content, $metadata, $configuration);
                } elseif ($processor instanceof ProcessorInterface) {
                    $content = $processor->process($content, $metadata, $configuration);
                } else {
                    throw new DomainException("Processor at index $index is not callable and does not implement  ProcessorInterface");
                }
            }
        };

        array_walk($contents, $callable);
    }
}
