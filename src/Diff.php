<?php

namespace Php\Project\Diff;

use function Php\Project\Parsers\getFileContents;
use function Php\Project\Parsers\getKeysOfStructure;

/**
 * Function genDiff is constructed based on how the files have changed
 * relative to each other, the keys are output in alphabetical order.
 *
 * @param string $pathFirst  path to first file
 * @param string $pathSecond path to second file
 *
 * @return string file differences in relation to each other
 */
function genDiff(string $pathFirst, string $pathSecond): string
{
    if (!is_readable($pathFirst) or !is_readable($pathSecond)) {
        exit("Error: The file(s) do not exist or are unreadable\n");
    }
    $firstFileContents = getFileContents($pathFirst);
    $secondFileContents = getFileContents($pathSecond);
    $differences = getDifference($firstFileContents, $secondFileContents, []);

    return getFormat($differences);
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

    foreach ($listAllKeys as $key) {
        $firstStructureKeyExists = property_exists($firstStructure, $key);
        $secondStructureKeyExists = property_exists($secondStructure, $key);
        $node = [];

        switch (true) {
            case $firstStructureKeyExists and $secondStructureKeyExists:
                // if обе структуры объекты делаем рекурсивный вызов getDifference
                if ($firstStructure -> $key === $secondStructure -> $key) {
                    $accumDifferences[] = getNode($key, $firstStructure -> $key, 'unchanged');
                } else {
                    $accumDifferences[] = getNode($key, $firstStructure -> $key, 'deleted');
                    $accumDifferences[] = getNode($key, $secondStructure -> $key, 'added');
                }
                break;
            case !$secondStructureKeyExists and $firstStructureKeyExists:
                $accumDifferences[] = getNode($key, $firstStructure -> $key, 'deleted');
                break;
            case !$firstStructureKeyExists and $secondStructureKeyExists:
                $accumDifferences[] = getNode($key, $secondStructure -> $key, 'added');
                break;
            default:
                exit('Error: Key is not exists!');
        }
    }

    return $accumDifferences;
}

/**
 * Function create node with name, value and type
 *
 * @param string $name name node
 * @param string $value value node
 * @param string $type type node may be 'unchanged' | 'deleted' | 'added'
 *
 * @return array<string> return node
 */
function getNode(string $name, mixed $value, string $type): array
{
    $node['name'] = $name;
    // ask mentor, phpstan: is_bool always will false
    $node['value'] = (is_bool($value)) ? var_export($value, true) : $value;
    $node['type'] = $type;

    return $node;
}
/**
 * Function formate differences two files on basic array of nodes,
 * for added string move prefix '+',
 * for deleted string - prefix '-',
 * for unchanged string - prefix ' '.
 *
 * @param array<mixed> $associativeArray
 *
 * @return string return formating string
 */
function getFormat(array $nodes): string
{
    $result = array_reduce(
        $nodes,
        function ($carry, $item) {
            $prefix = match ((string) $item['type']) {
                'unchanged' => ' ', // delete ? ask mentor
                'deleted' => '-',
                'added' => '+',
                default => ' '
            };
            $carry .= "  {$prefix} {$item['name']}: {$item['value']}\n";
            return $carry;
        },
        ''
    );

    return "{\n{$result}}\n";
}
