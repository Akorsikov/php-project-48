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

    switch (pathinfo($path)['extension']) {
        case 'yaml' or 'yml':
            $object = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            $content = json_encode($object);
            //no break
    }

    return json_decode($content, true);
}
