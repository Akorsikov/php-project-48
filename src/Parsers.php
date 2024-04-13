<?php

namespace Php\Project\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Function receives the JSON or YML/YAML file content and decodes it into an associative array
 *
 * @param string $path path to JSON-file
 *
 * @return array<string> associative array
 */
function getFileContents(string $path): array
{
    $content = (string) file_get_contents($path);

    if (in_array(pathinfo($path)['extension'], ['yml', 'yaml'], true)) {
        $object = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        $content = json_encode($object);
    }

    return json_decode($content, true);
}
