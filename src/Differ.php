<?php

namespace Differ\Differ;

use Exception;

use function Differ\Helpers\getFileContents;
use function Differ\Helpers\getKeysOfStructure;
use function Differ\Helpers\sortArray;
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
        $differences = getDifference($firstFileContents, $secondFileContents);
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
 *
 * @return array<mixed> array like this:
 * [
 *  'name'  => '<name of object's property>',
 *  'value' => '<value of object's property>',
 *  'type'  => 'unchanged | deleted | added'
 * ]
 */
function getDifference(object $firstStructure, object $secondStructure): array
{
    return array_reduce(
        getSortedListAllKeys($firstStructure, $secondStructure),
        function ($carry, $item) use ($firstStructure, $secondStructure) {
            $firstStructureKeyExists = property_exists($firstStructure, (string) $item);
            $secondStructureKeyExists = property_exists($secondStructure, (string) $item);

            switch (true) {
                case $firstStructureKeyExists && $secondStructureKeyExists:
                    if (is_object($firstStructure -> $item) && is_object($secondStructure -> $item)) {
                        $nestedSructure = getDifference($firstStructure -> $item, $secondStructure -> $item);

                        $newNodes = array_merge($carry, [getNode($item, $nestedSructure, 'unchanged')]);
                    } elseif ($firstStructure -> $item === $secondStructure -> $item) {
                        $newNodes = array_merge($carry, [getNode($item, $firstStructure -> $item, 'unchanged')]);
                    } else {
                        $newNodes = array_merge(
                            $carry,
                            [getNode($item, $firstStructure -> $item, 'deleted')],
                            [getNode($item, $secondStructure -> $item, 'added')]
                        );
                    }
                    break;
                case !$secondStructureKeyExists && $firstStructureKeyExists:
                    $newNodes = array_merge($carry, [getNode($item, $firstStructure -> $item, 'deleted')]);
                    break;
                default:
                    $newNodes = array_merge($carry, [getNode($item, $secondStructure -> $item, 'added')]);
            }
            return $newNodes;
        },
        []
    );
}

/**
 * The function returns a sorted list of all keys of passed structures (trees)
 *
 * @param object $firstTree first structure (tree)
 * @param object $secondTree second structure (tree)
 *
 * @return array<int|string> sorted list of all keys of passed structures (trees)
 */
function getSortedListAllKeys(object $firstTree, object $secondTree): array
{
    $firstStructureKeys = getKeysOfStructure($firstTree);
    $secondStructureKeys = getKeysOfStructure($secondTree);
    $listAllKeys = array_unique(array_merge($firstStructureKeys, $secondStructureKeys));

    return sortArray($listAllKeys);
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
    if (is_array($value)) {
        $children = json_decode((string) json_encode($value), true);
        $newValue = '';
    } elseif (is_object($value)) {
        $newValue = json_decode((string) json_encode($value), true);
    } else {
        $newValue = (is_bool($value) || is_null($value)) ?
        strtolower(var_export($value, true)) :
        $value;
    }

    return isset($children) ?
        [
            'name' => $name,
            'type' => $type,
            'children' => $children
        ] :
        [
            'name' => $name,
            'type' => $type,
            'value' => $newValue
        ];
}
