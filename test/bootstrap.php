<?php

include_once(__DIR__ . "/../vendor/autoload.php");
include_once(__DIR__ . "/DirectoryAutoLoader.php");

DirectoryAutoloader::registerDirectory(__DIR__ . "/src", "pjdietz\\Pages\\Test\\", true);
