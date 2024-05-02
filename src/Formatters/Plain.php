<?php

namespace Differ\Formaters\Plain;

/**
 * Function formate differences two files on base array of nodes,
 * example:
 * Property 'common.follow' was added with value: false
 * Property 'common.setting2' was removed
 * Property 'common.setting3' was updated. From true to null
 * Property 'common.setting4' was added with value: 'blah blah'
 * Property 'common.setting5' was added with value: [complex value]
 * Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
 * Property 'common.setting6.ops' was added with value: 'vops'
 * Property 'group1.baz' was updated. From 'bas' to 'bars'
 * Property 'group1.nest' was updated. From [complex value] to 'str'
 * Property 'group2' was removed
 * Property 'group3' was added with value: [complex value]
 *
 * @param array<mixed> $nodes node describing the differences between the two structures
 *
 * @return string return formating string in plain style
 */
function plain(array $nodes, string $path = ''): string
{
    $result = '';
    $lengthNodes = count($nodes);
    for ($i = 0; $i < $lengthNodes; $i++) {
        $nameNode = $path;
        if (array_key_exists('type', $nodes[$i]) and $nodes[$i]['type'] !== 'uncanged') {
            $nameNode .= "{$nodes[$i]['name']}.";

            $typeCurNode = $nodes[$i]['type'];
            $typePrevNode = ($i > 0) ? $nodes[$i - 1]['type'] : null;
            $typeNextNode = ($i < ($lengthNodes - 1)) ? $nodes[$i + 1]['type'] : null;

            if (array_key_exists('children', $nodes[$i])) {
                $result .= plain($nodes[$i]['children'], $nameNode);
            } else {
                $nameNode = rtrim($nameNode, '.');
                $value = getNormalisedValue($nodes[$i]['value']);
                if ($typeCurNode === 'deleted') {
                    if ($typeNextNode === 'added' and $nodes[$i]['name'] === $nodes[$i + 1]['name']) {
                        $newValue = getNormalisedValue($nodes[$i + 1]['value']);
                        $result .= "Property '{$nameNode}' was updated. From {$value} to {$newValue}\n";
                    } else {
                        $result .= "Property '{$nameNode}' was removed\n";
                    }
                } elseif ($typeCurNode === 'added') {
                    if ($i === 0 or $typePrevNode !== 'deleted' or $nodes[$i]['name'] !== $nodes[$i - 1]['name']) {
                        $result .= "Property '{$nameNode}' was added with value: {$value}\n";
                    }
                }
            }
        }
    }
    return $result;
}
/**
 * Function returns [complex value] instead of the argument $value,
 * if the argument is an array or adds quotes to the argument
 * if the argument is not a number or one of the following values:
 * 'true', 'false', 'null'. Otherwise returns the argument as is.
 *
 * @param mixed $value
 *
 * @return float|int|string
 */
function getNormalisedValue(mixed $value): float|int|string
{
    if (is_array($value)) {
        return '[complex value]';
    } elseif (!in_array($value, ['true', 'false', 'null'], true) and !is_numeric($value)) {
        return "'{$value}'";
    } else {
        return $value;
    }
}
