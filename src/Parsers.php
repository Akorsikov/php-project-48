<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Function converts json or yaml/yml file contents into an object
 *
 * @param string $fileContent content of file
 * @param string $extension extention of file
 *
 * @return object object with recursive structure
 */
function parser(string $fileContent, string $extension): object
{
    switch ($extension) {
        case 'json':
            return json_decode((string) $fileContent, false);
        case 'yaml':
            return Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception(
                "Error: Invalid file extension '{$extension}', use json- or yaml/yml- files !\n"
            );
    }
}
