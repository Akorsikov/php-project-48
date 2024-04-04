<?php

namespace Php\Project\Tests;

use PHPUnit\Framework\TestCase;
use Php\Project\Diff;

use function Php\Project\Diff\genDiff;

class UserTest extends TestCase
{
    public function testGenDiff(): void
    {
        $path1 = './tests/file1.json';
        $path2 = './tests/file2.json';
        $differences = "{\n  - follow: false\n    host: hexlet.io\n  - proxy: 123.234.53.22\n  - timeout: 50\n  + timeout: 20\n  + verbose: true\n}\n";

        $this->assertEquals($differences, genDiff($path1, $path2));
    }
}