<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    // Difference between two json-files with 'stylish' formatter
    public function testGenDiffJsonJsonFormatDefault(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/stylishDiff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2));
    }

    // Difference between two json-files with 'plain' formatter
    public function testGenDiffJsonJsonFormatPlain(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/plainDiff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'plain'));
    }

    // Difference between two json-files with 'stylish' formatter
    public function testGenDiffJsonJsonFormatStylish(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/stylishDiff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between json & yaml - files with 'stylish' formatter
    public function testGenDiffJsonYamlFormatStylish(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/stylishDiff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between two yaml-files with 'stylish' formatter
    public function testGenDiffYamlYamlFormatStylish(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/stylishDiff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'stylish'));
    }

    // Difference between yml & yaml-files with 'plain' formatter
    public function testGenDiffYmlYamlFormatPlain(): void
    {
        $path1 = './tests/fixtures/file1.yml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/plainDiff.txt';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'plain'));
    }

    // Difference between two json-files with 'json-formatter'
    public function testGenDiffJsonJsonFormatJson(): void
    {
        $path1 = './tests/fixtures/file1.json';
        $path2 = './tests/fixtures/file2.json';
        $fileDiff = './tests/fixtures/jsonDiff.json';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'json'));
    }

    // Difference between two yaml-files with 'json-formatter'
    public function testGenDiffYamlYamlFormatJson(): void
    {
        $path1 = './tests/fixtures/file1.yaml';
        $path2 = './tests/fixtures/file2.yaml';
        $fileDiff = './tests/fixtures/jsonDiff.json';

        $this->assertStringEqualsFile($fileDiff, genDiff($path1, $path2, 'json'));
    }
}
