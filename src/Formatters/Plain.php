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
    return array_reduce($nodes, function ($carry, $item) use ($path) {
        $nameNode = implode('', [$path, "{$item['name']}."]);

        if (array_key_exists('children', $item)) {
            return implode('', [$carry, plain($item['children'], $nameNode)]);
        }
        if ($item['type'] === 'deleted') {
            return getTextForProperty('deleted', rtrim($nameNode, '.'), $carry);
        }
        if ($item['type'] === 'added') {
                return getTextForProperty('added', rtrim($nameNode, '.'), $carry, getNormalizedValue($item));
        }
        if ($item['type'] === 'changed') {
            return getTextForProperty('changed', rtrim($nameNode, '.'), $carry, getNormalizedValue($item));
        }
        return $carry;
    }, '');
}

/**
 * The function returns the text for the property depending on
 * the specified type 'added' or 'deleted' or 'updated'
 *
 * @param string $type for choice of kind text
 * @param string $nameProperty
 * @param string $textAccumulator
 * @param array<mixed> $value array from old value and new value of property
 *
 * @return string
 */
function getTextForProperty(string $type, string $nameProperty, string $textAccumulator, array $value = []): string
{
    return match ($type) {
        'deleted' => implode(
            '',
            [$textAccumulator, "Property '{$nameProperty}' was removed\n"]
        ),
        'changed' => implode(
            '',
            [$textAccumulator, "Property '{$nameProperty}' was updated. From {$value[0]} to {$value[1]}\n"]
        ),
        'added' => implode(
            '',
            [$textAccumulator, "Property '{$nameProperty}' was added with value: {$value[0]}\n"]
        ),
        default => throw new \Exception("Error: There is no such state -'{$type}' for the properties being compared!\n")
    };
}

/**
 * Function returns [complex value] instead of the argument $value,
 * if the argument is an array or adds quotes to the argument
 * if the argument is not a number or one of the following values:
 * 'true', 'false', 'null'. Otherwise returns the argument as is.
 *
 * @param array<mixed> $node
 *
 * @return array<mixed>
 */
function getNormalizedValue(array $node): array
{
    if ($node['type'] === 'changed') {
        $oldValue = getChangedValue($node, 'oldValue');
        $newValue = getChangedValue($node, 'newValue');
        return [$oldValue, $newValue];
    }
    $value = $node['value'];

    if (is_array($value)) {
        return ['[complex value]'];
    }

    if (
        !in_array($value, ['true', 'false', 'null'], true) &&
        !is_numeric($value)
    ) {
        return ["'{$value}'"];
    }

    return [$value];
}

/**
 * Function returns one of tree node values depending on
 * passed key (‘oldValue’ | ‘newValue’).
 *
 * @param array<mixed> $node node of tree
 * @param string $key key of values
 *
 * @return mixed normalized value from tree node with type
 * 'changed'
 */
function getChangedValue(array $node, $key): mixed
{
    if (is_array($node[$key])) {
        return '[complex value]';
    } elseif (
        !in_array($node[$key], ['true', 'false', 'null'], true) &&
        !is_numeric($node[$key])
    ) {
        return "'{$node[$key]}'";
    }
    return $node[$key];
}
