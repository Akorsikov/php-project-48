<?php

namespace Differ\Differ;

use Exception;

use function Differ\Helpers\getFileContents;
use function Differ\Helpers\getKeysOfStructure;
use function Differ\Formaters\choceFormatter;

/**
 * Function genDiff is constructed based on how the files have changed
 * relative to each other, the keys are output in alphabetical order.
 *
 * @param string $pathFirst  path to first file
 * @param string $pathSecond path to second file
 * @param string $formatter style formating
 *
 * @return string file differences in relation to each other
 */
function genDiff(string $pathFirst, string $pathSecond, string $formatter = 'stylish'): string
{
    try {
        $firstFileContents = getFileContents($pathFirst);
        $secondFileContents = getFileContents($pathSecond);
        $differences = getDifference($firstFileContents, $secondFileContents, []);
        $outputDiff = choceFormatter($differences, $formatter);
    } catch (\Exception $exception) {
        echo ($exception);
        $outputDiff = "\n";
    }
    return $outputDiff;
}

/**
 * Function compares two files (JSON or YML|YAML) and creates an array of differences for further formatting
 *
 * @param object $firstStructure original object, before changes
 * @param object $secondStructure final object, after changes
 * @param array<string> $accumDifferences
 *
 * @return array<mixed> array like this:
 * [
 *  'name'  => '<name of object's property>',
 *  'value' => '<value of object's property>',
 *  'type'  => 'unchanged | deleted | added'
 * ]
 */
function getDifference(object $firstStructure, object $secondStructure, array $accumDifferences): array
{
    $firstStructureKeys = getKeysOfStructure($firstStructure);
    $secondStructureKeys = getKeysOfStructure($secondStructure);
    $listAllKeys = array_unique(array_merge($firstStructureKeys, $secondStructureKeys));
    sort($listAllKeys, SORT_STRING);

    $accumDifferences = array_reduce(
        $listAllKeys,
        function ($carry, $item) use ($firstStructure, $secondStructure) {
            $firstStructureKeyExists = property_exists($firstStructure, (string) $item);
            $secondStructureKeyExists = property_exists($secondStructure, (string) $item);

            switch (true) {
                case $firstStructureKeyExists and $secondStructureKeyExists:
                    if (is_object($firstStructure -> $item) and is_object($secondStructure -> $item)) {
                        $nestedSructure = getDifference($firstStructure -> $item, $secondStructure -> $item, []);
                        $carry[] = getNode($item, $nestedSructure, 'unchanged');
                    } elseif ($firstStructure -> $item === $secondStructure -> $item) {
                            $carry[] = getNode($item, $firstStructure -> $item, 'unchanged');
                    } else {
                        $carry[] = getNode($item, $firstStructure -> $item, 'deleted');
                        $carry[] = getNode($item, $secondStructure -> $item, 'added');
                    }
                    break;
                case !$secondStructureKeyExists and $firstStructureKeyExists:
                    $carry[] = getNode($item, $firstStructure -> $item, 'deleted');
                    break;
                default:
                    $carry[] = getNode($item, $secondStructure -> $item, 'added');
            }
            return $carry;
        },
        []
    );

    return $accumDifferences;
}

/**
 * Function create node with name, value and type
 *
 * @param string $name name node
 * @param mixed $value value node
 * @param string $type type node may be 'unchanged' | 'deleted' | 'added'
 *
 * @return array<string> return node
 */
function getNode(int|string $name, mixed $value, string $type): array
{
    $node['name'] = $name;
    $node['type'] = $type;
    if (is_array($value)) {
        $node['children'] = json_decode((string) json_encode($value), true);
    } elseif (is_object($value)) {
        $node['value'] = json_decode((string) json_encode($value), true);
    } else {
        $node['value'] = (is_bool($value) or is_null($value)) ? strtolower(var_export($value, true)) : $value;
    }

    return $node;
}
