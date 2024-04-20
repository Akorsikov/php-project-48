<?php

namespace Php\Project\Tests;

use PHPUnit\Framework\TestCase;
use Php\Project\Diff;

use function Php\Project\Diff\genDiff;
use function Php\Project\Diff\getFormat;

class UserTest extends TestCase
{
    public function testGenDiff(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/diff-12.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }

    public function testGenDiff2(): void
    {
        $path1 = './tests/fixtures/flat-file2.json';
        $path2 = './tests/fixtures/flat-file1.json';
        $fileDiff = './tests/fixtures/flat-diff-21.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }

public function testGenDiffFlatFiles(): void
{
    $path1 = './tests/fixtures/flat-file1.json';
    $path2 = './tests/fixtures/flat-file2.json';
    $fileDiff = './tests/fixtures/flat-diff.txt';

    $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
}

public function testGenDiffYalmFiles(): void
{
    $path1 = './tests/fixtures/file1.yaml';
    $path2 = './tests/fixtures/file2.yaml';
    $fileDiff = './tests/fixtures/diff-12.txt';

    $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
}
    public function testGenDiffYmlFiles(): void
    {
        $path1 = './tests/fixtures/flat-file1.yml';
        $path2 = './tests/fixtures/flat-file2.yml';
        $fileDiff = './tests/fixtures/flat-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }
}