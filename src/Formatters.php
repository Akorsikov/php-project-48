<?php

namespace Differ\Formaters;

use function Differ\Formaters\Plain\plain;
use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Json\Formater\jsonFormatter;

/**
 * Function formats the difference array of two files
 * for displaying on the screen.
 *
 * @param array<mixed> $differences difference array of two files
 * @param string $nameFormatter 'plain' or 'stylish'
 *
 * @return string for displaying on the screen
 */
function choceFormatter(array $differences, string $nameFormatter): string
{
    switch ($nameFormatter) {
        case 'stylish':
            $result = stylish($differences);
            return "{$result}\n";
        case 'plain':
            return plain($differences);
        case 'json':
            $result = jsonFormatter($differences);
            return "{$result}\n";
        // add more formatters
        // case '<other formatter>':
        //     return <other formatter>($differences);
        default:
            throw new \Exception("Error: There is no such '{$nameFormatter}' formatter!\n");
    }
}
