<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Function converts json or yaml/yml file contents into an object
 *
 * @param string $fileContent content of file
 * @param string $format extention of file
 *
 * @return object object with recursive structure
 */
function parser(string $fileContent, string $format): object
{
    return match ($format) {
        'json' => json_decode($fileContent, false),
        'yaml' => Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP),
        default => throw new \Exception(
            "Error: Invalid file extension '{$format}', use json- or yaml/yml- files !\n"
        )
    };
}
