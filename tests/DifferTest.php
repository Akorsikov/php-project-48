<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    // Difference between two json-files with 'stylish' formatter
    public function testGenDiff(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two json-files with 'plain' formatter
    public function testGenDiff2(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/plain-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'plain'));
    }

    // Difference between two json-files with default formatter
    public function testGenDiff4(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }

    // Difference between json & yaml - files with 'stylish' formatter
    public function testGenDiff5(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two yaml-files with 'stylish' formatter
    public function testGenDiff6(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two yaml-files with 'plain' formatter
    public function testGenDiff7(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/plain-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'plain'));
    }

    // Difference between two json-files with 'json-formatter'
    public function testGenDiff8(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/json-diff.json';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'json'));
    }
}