<?php

namespace Differ\Formatters\Stylish;

const NUMBER_INDENT_PER_LEVEL_FOR_TEXT = 4;
const NUMBER_INDENT_PER_LEVEL_FOR_BRACKETS = 2;
const SYMBOL_OF_INDENT = ' ';

/**
 * Function - wrapper, call recursive function.
 *
 * @param array<mixed> $nodes node describing the differences between the two structures
 *
 * @return string return formating string and moves to a new line
 */
function stylish(array $nodes): string
{
    $result = format($nodes);

    return "{$result}\n";
}

/**
 * Function format differences two files on base array of nodes,
 * for added string move prefix '+',
 * for deleted string - prefix '-',
 * for unchanged string - prefix ' '.
 *
 * @param array<mixed> $nodes node describing the differences between the two structures
 * @param int $level nesting depth
 *
 * @return string return formating string
 */
function format(array $nodes, int $level = 1): string
{
    $result = array_reduce(
        $nodes,
        function ($carry, $item) use ($level) {
            $indent = getIndent($level);

            if ($item['type'] === 'changed') {
                $value1 = getChangedValue($item, 'value1', $level);
                $value2 = getChangedValue($item, 'value2', $level);
                return implode(
                    '',
                    [$carry,
                    "{$indent}- {$item['name']}: {$value1}\n",
                    "{$indent}+ {$item['name']}: {$value2}\n"]
                );
            }
            $value = getValue($item, $level);
            $prefix = getPrefix($item['type']);

            return implode(
                '',
                [$carry,
                "{$indent}{$prefix} {$item['name']}: {$value}\n"]
            );
        },
        ''
    );
    $indent = getIndent($level, true);

    return "{\n{$result}{$indent}}";
}

/**
 * Function formats nested arrays
 *
 * @param array<mixed> $array nested array
 * @param int $level level nested
 *
 * @return string return formating string
 */
function getFormatArray(array $array, int $level): string
{
    $listKeys = array_keys($array);

    $string = array_reduce(
        $listKeys,
        function ($carry, $item) use ($array, $level) {
            if (is_array($array[$item])) {
                $value = getFormatArray($array[$item], $level + 1);
            } else {
                $value = $array[$item];
            }
            $indent = getIndent($level);

            return implode(
                '',
                [ $carry,
                "{$indent}  {$item}: {$value}\n"]
            );
        },
        ''
    );
    $indent = getIndent($level, true);

    return "{\n{$string}{$indent}}";
}

/**
 *
 * Function returns indentation depending on the nesting depth and
 * the presence of the flag $forBrackets
 *
 * @param int  $level nesting depth
 * @param bool $isBrackets decreases the left indent by two characters for clousere brackets
 *
 * @return string margin the left from spaces for differences and brackets
 */
function getIndent(int $level, bool $isBrackets = false): string
{
    $indentToLeft = $isBrackets ?
        NUMBER_INDENT_PER_LEVEL_FOR_TEXT :
        NUMBER_INDENT_PER_LEVEL_FOR_BRACKETS;
    $indent = $level * NUMBER_INDENT_PER_LEVEL_FOR_TEXT - $indentToLeft;

    return str_repeat(SYMBOL_OF_INDENT, $indent);
}

/**
 * function returns a prefix depending on node type
 *
 * @param string $itemType node type
 *
 * @return string symbol of prefix (' ' | '-' | '+')
 */
function getPrefix(string $itemType): string
{
    return match ($itemType) {
        'unchanged' => ' ',
        'deleted' => '-',
        'added' => '+',
        default => throw new \Exception(
            "Error: Unknown property state type - '{$itemType}'!"
        )
    };
}

/**
 * Function returns the value of the node
 *
 * @param array<mixed> $node
 * @param int $level nesting level
 *
 * @return mixed
 */
function getValue(array $node, int $level): mixed
{
    if (array_key_exists('children', $node)) {
        return format($node['children'], $level + 1);
    } elseif (array_key_exists('value', $node)) {
        if (is_array($node['value'])) {
            return getFormatArray($node['value'], $level + 1);
        }
    }
    return $node['value'];
}

/**
 * Function returns the value of the node with 'changed' type
 *
 * @param array<mixed> $node
 * @param int $level nesting level
 *
 * @return mixed
 */
function getChangedValue(array $node, string $keyValue, int $level): mixed
{
    if (is_array($node[$keyValue])) {
        $value = getFormatArray($node[$keyValue], $level + 1);
    } else {
        $value = $node[$keyValue];
    }
    return $value;
}
