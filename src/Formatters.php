<?php

namespace Differ\Formaters;

use function Differ\Formaters\Plain\plain;
use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Json\json;

/**
 * Function formats the difference array of two files
 * for displaying on the screen.
 *
 * @param array<mixed> $differences difference array of two files
 * @param string $formatter 'plain' or 'stylish'
 *
 * @return string for displaying on the screen
 */
function format(array $differences, string $formatter): string
{
    return match ($formatter) {
        'stylish' => stylish($differences),
        'plain' => plain($differences),
        'json' => json($differences),
        default => throw new \Exception("Error: There is no such '{$formatter}' formatter!")
    };
}
