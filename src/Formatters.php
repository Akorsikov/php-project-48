<?php

namespace Php\Project\Formaters;

use function Php\Project\Formaters\Plain\plain;
use function Php\Project\Formatters\Stylish\stylish;

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
            return stylish($differences, 1);
        case 'plain':
            return plain($differences);
        case 'json':
            return (string) json_encode($differences);
        // add more formatters
        // case '<other formatter>':
        //     return <other formatter>($differences);
        default:
            exit("Error: There is no such formatter!\n");
    }
}
