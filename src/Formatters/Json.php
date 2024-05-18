<?php

namespace Differ\Formatters\Json;

/**
 * Function formate input array to string with json format
 *
 * @param array<mixed> $nodes array with recursive structure
 *
 * @return string with json format
 */
function json(array $nodes): string
{
    $result = (string) json_encode($nodes);

    return "{$result}\n";
}
