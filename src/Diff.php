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

    return getFormat($differences, 1);
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


        switch (true) {
            case $firstStructureKeyExists and $secondStructureKeyExists:
                if (is_object($firstStructure -> $key) and is_object($secondStructure -> $key)) {
                    $nestedSructure = getDifference($firstStructure -> $key, $secondStructure -> $key, []);
                    // var_dump('Nested structure: ', $nestedSructure);
                    $accumDifferences[] = getNode($key, $nestedSructure, 'unchanged');
                } elseif ($firstStructure -> $key === $secondStructure -> $key) {
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
 * @param mixed $value value node
 * @param string $type type node may be 'unchanged' | 'deleted' | 'added'
 *
 * @return array<string> return node
 */
function getNode(string $name, mixed $value, string $type): array
{
    $node['name'] = $name;
    $node['type'] = $type;
    if (is_array($value)) {
        $node['children'] = json_decode(json_encode($value), true);
    } elseif (is_object($value)) {
        $node['value'] = json_decode(json_encode($value), true);
    } else {
        // ask mentor, phpstan: is_bool always will false
        $node['value'] = (is_bool($value) or is_null($value)) ? strtolower(var_export($value, true)) : $value;
    }

    return $node;
}
/**
 * Function formate differences two files on base array of nodes,
 * for added string move prefix '+',
 * for deleted string - prefix '-',
 * for unchanged string - prefix ' '.
 *
 * @param array<mixed> $nodes node describing the differences between the two structures
 * @param int          $level nesting depth
 *
 * @return string return formating string
 */
function getFormat(array $nodes, int $level): string
{
    $result = '';
    foreach ($nodes as $item) {
        if (array_key_exists('children', $item)) {
            $value = getFormat($item['children'], $level + 1);
        } elseif (array_key_exists('value', $item)) {
            if (is_array($item['value'])) {
                $value = getFormatArray($item['value'], $level + 1);
            } else {
                $value = $item['value'];
            }
        } else {
            var_dump('ITem: ', $item);
        }
        $prefix = match ($item['type']) {
            'unchanged' => ' ',
            'deleted' => '-',
            'added' => '+',
            default => ' '
        };
        $margin = getMargin($level);
        $result .= empty($value) ?
            "{$margin}{$prefix} {$item['name']}:\n" :
            "{$margin}{$prefix} {$item['name']}: {$value}\n";
    }
    $margin = getMargin($level, true);

    return "{\n{$result}{$margin}}";
}

/**
 * Function formats nested arrays
 *
 * @param array<mixed> $array nested array
 * @param int   $level level nested
 *
 * @return string return formating string
 */
function getFormatArray(array $array, $level): string
{
    $string = '';
    $listKeys = array_keys($array);
    foreach ($listKeys as $key) {
        if (is_array($array[$key])) {
            $value = getFormatArray($array[$key], $level + 1);
            $margin = getMargin($level);
            $string .= empty($value) ?
            "  {$margin}{$key}:\n" :
            "  {$margin}{$key}: {$value}\n";
        } else {
            $margin = getMargin($level);
            $string .= empty($array[$key]) ?
            "  {$margin}{$key}:\n" :
            "  {$margin}{$key}: {$array[$key]}\n";
        }
    }
    // $margin = substr($margin, 2);
    $margin = getMargin($level, true);
    return "{\n{$string}{$margin}}";
}

/**
 * function returns indentation depending on the nesting depth and
 * the presence of the flag $isBrackets
 *
 * @param int  $level nesting depth
 * @param bool $isBrackets decreases the left indent by two characters for clousere brackets
 *
 * @return string margin the left from spaces for differences and brackets
 */
function getMargin(int $level, bool $isBrackets = false): string
{
    $numberSpacePerLevel = 4;
    $marginToLeft = $isBrackets ? 4 : 2;
    $margin = $level * $numberSpacePerLevel - $marginToLeft;
    return str_repeat(' ', $margin);
}
