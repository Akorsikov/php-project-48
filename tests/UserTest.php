<?php

namespace Php\Project\Tests;

use PHPUnit\Framework\TestCase;
use Php\Project\Diff;

use function Php\Project\Diff\genDiff;

class UserTest extends TestCase
{
//     public function testGenDiff(): void
//     {
//         $path1 = './tests/fixtures/file1.json';
//         $path2 = './tests/fixtures/file2.json';
//         $fileDiff = './tests/fixtures/difference.txt';

//         $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
//     }

//     public function testGenDiff2(): void
//     {
//         $path1 = './tests/fixtures/file2.json';
//         $path2 = './tests/fixtures/file1.json';
//         $fileDiff = './tests/fixtures/difference2.txt';

//         $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
//     }

public function testGenDiffFlatFiles(): void
{
    $path1 = './tests/fixtures/flat-file1.json';
    $path2 = './tests/fixtures/flat-file2.json';
    $fileDiff = './tests/fixtures/difference.txt';

    $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
}

public function testGenDiffFlatFiles2(): void
{
    $path1 = './tests/fixtures/flat-file2.json';
    $path2 = './tests/fixtures/flat-file1.json';
    $fileDiff = './tests/fixtures/difference2.txt';

    $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
}
    public function testGenDiffYmlFiles(): void
    {
        $path1 = './tests/fixtures/file1.yml';
        $path2 = './tests/fixtures/file2.yml';
        $fileDiff = './tests/fixtures/difference.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }
}