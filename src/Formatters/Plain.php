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
    return array_reduce($nodes, function ($carry, $item) use ($path, $nodes) {
        if (array_key_exists('type', $item) or $item['type'] !== 'uncanged') {
            $nameNode = implode('', [$path, "{$item['name']}."]);
            $typeCurNode = $item['type'];
            $prevNode = getPrevNode($item, $nodes);
            $nextNode = getNextNode($item, $nodes);
            $typePrevNode = (is_null($prevNode)) ? null : $prevNode['type'];
            $typeNextNode = (is_null($nextNode)) ? null : $nextNode['type'];
            $namePrevNode = (is_null($prevNode)) ? null : $prevNode['name'];
            $nameNextNode = (is_null($nextNode)) ? null : $nextNode['name'];
            if (array_key_exists('children', $item)) {
                return implode('', [$carry, plain($item['children'], $nameNode)]);
            } else {
                $value = getNormalizedValue($item);
                if ($typeCurNode === 'deleted') {
                    if ($typeNextNode === 'added' and $item['name'] === $nameNextNode) {
                        $newValue = getNormalizedValue($nextNode);
                        return getTextForProperty('updated', rtrim($nameNode, '.'), $carry, [$value, $newValue]);
                    } else {
                        return getTextForProperty('deleted', rtrim($nameNode, '.'), $carry);
                    }
                } elseif ($typeCurNode === 'added') {
                    if ($typePrevNode !== 'deleted' or $item['name'] !== $namePrevNode) {
                        return getTextForProperty('added', rtrim($nameNode, '.'), $carry, [$value]);
                    }
                }
            }
        }
        return $carry;
    }, '');
}

/**
 * Function returns an element (node) of an array (tree)
 * preceding the given element in this array.
 *
 * @param array<mixed> $itemNodes Element (node)
 * relative to which the preceding element is searched for.
 * @param array<mixed> $nodes The array in which the search
 * is performed.
 *
 * @return array<mixed>|null The element (node) of the array (tree)
 * preceding the given element. Null if the given element is the first.
 */
function getPrevNode(array $itemNodes, array $nodes, int $index = 0): array|null
{
    if ($itemNodes === $nodes[$index]) {
        if ($index === 0) {
            return null;
        }
        return $nodes[$index - 1];
    }
    return getPrevNode($itemNodes, $nodes, $index + 1);
}

/**
 * The function returns an element (node) of an array (tree) following
 * the specified element in this array.
 *
 * @param array<mixed> $itemNodes The element relative to which
 * the next element is searched.
 * @param array<mixed> $nodes The array in which the search is performed.
 *
 * @return array<mixed>|null The element (node) of the array (tree)
 * following the specified element. Null if the given element is the last one.
 */
function getNextNode(array $itemNodes, array $nodes, int $index = 0): array|null
{
    if ($itemNodes === $nodes[$index]) {
        if ($index === count($nodes) - 1) {
            return null;
        }
        return $nodes[$index + 1];
    }
    return getNextNode($itemNodes, $nodes, $index + 1);
}

/**
 * The function returns the text for the property depending on
 * the specified type 'added' or 'deleted' or 'updated'
 *
 * @param string $type for choice of kind text
 * @param string $nameProperty
 * @param string $textAccumulater
 * @param array<mixed> $value array from old value and new value of property
 *
 * @return string
 */
function getTextForProperty(string $type, $nameProperty, $textAccumulater, array $value = []): string
{
    return match ($type) {
        'deleted' => implode(
            '',
            [$textAccumulater, "Property '{$nameProperty}' was removed\n"]
        ),
        'updated' => implode(
            '',
            [$textAccumulater, "Property '{$nameProperty}' was updated. From {$value[0]} to {$value[1]}\n"]
        ),
        default => implode(
            '',
            [$textAccumulater, "Property '{$nameProperty}' was added with value: {$value[0]}\n"]
        )
    };
}

/**
 * Function returns [complex value] instead of the argument $value,
 * if the argument is an array or adds quotes to the argument
 * if the argument is not a number or one of the following values:
 * 'true', 'false', 'null'. Otherwise returns the argument as is.
 *
 * @param array<mixed>|null $node
 *
 * @return float|int|string
 */
function getNormalizedValue(array|null $node): float|int|string|null
{
    if (is_array($node) and array_key_exists('value', $node)) {
        $value = $node['value'];

        if (is_array($value)) {
            return '[complex value]';
        }

        if (!in_array($value, ['true', 'false', 'null'], true) and !is_numeric($value)) {
            return "'{$value}'";
        }

        return $value;
    }

    return null;
}
