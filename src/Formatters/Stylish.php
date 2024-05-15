<?php

namespace Differ\Formatters\Stylish;

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
function stylish(array $nodes, int $level = 1): string
{
    $result = array_reduce(
        $nodes,
        function ($carry, $item) use ($level) {
            if (array_key_exists('children', $item)) {
                $value = stylish($item['children'], $level + 1);
            } elseif (array_key_exists('value', $item)) {
                if (is_array($item['value'])) {
                    $value = getFormatArray($item['value'], $level + 1);
                } else {
                    $value = $item['value'];
                }
            } else {
                $value = '';
            }
            $prefix = match ($item['type']) {
                'unchanged' => ' ',
                'deleted' => '-',
                'added' => '+',
                default => ' '
            };
            $margin = getMargin($level);

            return implode(
                '',
                [ $carry,
                "{$margin}{$prefix} {$item['name']}: {$value}\n"]
            );
        },
        ''
    );
    $margin = getMargin($level, true);

    return "{\n{$result}{$margin}}";
}

/**
 * Function formats nested arrays
 *
 * @param array<mixed> $array nested array
 * @param int $level level nested
 *
 * @return string return formating string
 */
function getFormatArray(array $array, $level): string
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
            $margin = getMargin($level);

            return implode(
                '',
                [ $carry,
                "{$margin}  {$item}: {$value}\n"]
            );
        },
        ''
    );
    $margin = getMargin($level, true);

    return "{\n{$string}{$margin}}";
}

/**
 *
 * Function returns indentation depending on the nesting depth and
 * the presence of the flag $forBrackets
 *
 * @param int  $level nesting depth
 * @param bool $forBrackets decreases the left indent by two characters for clousere brackets
 *
 * @return string margin the left from spaces for differences and brackets
 */
function getMargin(int $level, bool $forBrackets = false): string
{
    $numberSpacePerLevel = 4;
    $marginToLeft = $forBrackets ? 4 : 2;
    $margin = $level * $numberSpacePerLevel - $marginToLeft;

    return str_repeat(' ', $margin);
}
