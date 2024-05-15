<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    // Difference between two json-files with 'stylish' formatter
    public function testGenDiffJsonJsonDefault(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two json-files with 'plain' formatter
    public function testGenDiffJsonJsonPlain(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/plain-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'plain'));
    }

    // Difference between two json-files with default formatter
    public function testGenDiffJsonJsonStylish(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }

    // Difference between json & yaml - files with 'stylish' formatter
    public function testGenDiffJsonYamlStylish(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two yaml-files with 'stylish' formatter
    public function testGenDiffYamlYamlStylish(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/stylish-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two yaml-files with 'plain' formatter
    public function testGenDiffYamlYamlPlain(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/plain-diff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'plain'));
    }

    // Difference between two json-files with 'json-formatter'
    public function testGenDiffJsonJsonJson(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/json-diff.json';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'json'));
    }

    // Difference between two yaml-files with 'json-formatter'
    public function testGenDiffYamlYamlJson(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/json-diff.json';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'json'));
    }
}
