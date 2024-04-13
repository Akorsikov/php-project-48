<?php

namespace Php\Project\Diff;

use function Php\Project\Parsers\getFileContents;

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
    $result = '';

    $firstFileKeys = array_keys($firstFileContents);
    $secondFileKeys = array_keys($secondFileContents);
    $listAllKeys = array_unique(array_merge($firstFileKeys, $secondFileKeys));
    sort($listAllKeys, SORT_STRING);

    foreach ($listAllKeys as $key) {
        $firstFileKeyExists = array_key_exists($key, $firstFileContents);
        $secondFileKeyExists = array_key_exists($key, $secondFileContents);

        if ($firstFileKeyExists) {
            $firstFileContents[$key] = var_export($firstFileContents[$key], true);
        }

        if ($secondFileKeyExists) {
            $secondFileContents[$key] = var_export($secondFileContents[$key], true);
        }

        switch (true) {
            case $firstFileKeyExists and $secondFileKeyExists:
                if ($firstFileContents[$key] === $secondFileContents[$key]) {
                    $result .= "    {$key}: {$firstFileContents[$key]}\n";
                } else {
                    $result .= "  - {$key}: {$firstFileContents[$key]}\n";
                    $result .= "  + {$key}: {$secondFileContents[$key]}\n";
                }
                break;
            case $firstFileKeyExists and !$secondFileKeyExists:
                $result .= "  - {$key}: {$firstFileContents[$key]}\n";
                break;
            case !$firstFileKeyExists and $secondFileKeyExists:
                $result .= "  + {$key}: {$secondFileContents[$key]}\n";
                break;
            default:
                exit('Error: Key is not exists!');
        }
    }
    return "{\n{$result}}\n";
}
