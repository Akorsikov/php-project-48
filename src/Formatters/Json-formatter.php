<?php

namespace Differ\Formatters\Json\Formater;

/**
 * Function formate input array to string with json format
 *
 * @param array<mixed> $nodes array with recursive structure
 *
 * @return string with json format
 */
function jsonFormatter(array $nodes): string
{
    return (string) json_encode($nodes);
}
