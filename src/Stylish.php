<?php

namespace Php\Project\Stylish;

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
function getFormat(array $nodes, int $level = 1): string
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
