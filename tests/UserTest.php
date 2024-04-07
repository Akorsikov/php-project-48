<?php

namespace Php\Project\Tests;

use PHPUnit\Framework\TestCase;
use Php\Project\Diff;

use function Php\Project\Diff\genDiff;

class UserTest extends TestCase
{
    public function testGenDiff(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/difference.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }
}