<?php

namespace Php\Project\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Function receives the JSON or YML/YAML file content and decodes it into an associative array
 *
 * @param string $path path to JSON-file
 *
 * @return object
 */
function getFileContents(string $path): object
{
    $content = (string) file_get_contents($path);
    $extention = pathinfo($path)['extension'];

    if ($extention === 'yml' or $extention === 'yaml') {
        $object = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        $content = json_encode($object);
    }

    return json_decode($content, false);
}
/**
 * @param object $structure
 *
 * @return array<string>
 */
function getKeysOfStructure(object $structure): array
{
    return array_keys(json_decode(json_encode($structure), true));
}
