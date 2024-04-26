<?php

namespace Differ\Parsers;

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
    $extention = pathinfo($path, PATHINFO_EXTENSION);

    if ($extention === 'yml' or $extention === 'yaml') {
    // или лучше: if (in_array($extention, ['yml', 'yaml'], true)) {
        $object = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        $content = json_encode($object);
    }

    return json_decode((string) $content, false);
}

/**
 * Function returned all keys of structure such object
 *
 * @param object $structure object
 *
 * @return array<int, int|string> all keys of object
 */
function getKeysOfStructure(object $structure): array
{
    return array_keys(json_decode((string) json_encode($structure), true));
}
