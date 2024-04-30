<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Function converts json or yaml/yml file contents into an object
 *
 * @param string $fileContent content of file
 * @param string $extention extention of file
 *
 * @return object object with recursive structure
 */
function parser(string $fileContent, string $extention): object
{
    if ($extention === 'yml' or $extention === 'yaml') {
        $object = Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
        $parceContent = json_encode($object);
    } else {
        $parceContent = $fileContent;
    }

    return json_decode((string) $parceContent, false);
}
