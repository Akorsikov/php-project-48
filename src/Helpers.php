<?php

namespace Differ\Helpers;

use function Differ\Parsers\parser;

/**
 * Function receives the JSON or YML/YAML file content and decodes it into an object
 *
 * @param string $filepath path to JSON-file
 *
 * @return object
 */
function getFileContents(string $filepath): object
{

    if (!is_readable($filepath)) {
        throw new \Exception("Error: The file '{$filepath}' do not exist or are unreadable!\n");
    }

    $content = (string) file_get_contents($filepath);
    $extention = pathinfo($filepath, PATHINFO_EXTENSION);

    return parser($content, $extention);
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

/**
 * Immutable array sorting function
 *
 * @param array<int|string> $array sorted array
 *
 * @return array<int|string> already sorted array
 */
function sortArray(array $array): array
{
    if (count($array) > 1) {
        $minItem = min($array);
        $subArray = array_filter($array, fn($item) => $item !== $minItem);
        return array_merge([$minItem], sortArray($subArray));
    } else {
        return $array;
    }
}
