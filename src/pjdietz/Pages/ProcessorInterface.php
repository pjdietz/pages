<?php

namespace pjdietz\Pages;

interface ProcessorInterface
{
    public function process($subject, $metadata = null, $configuration = null);
}
