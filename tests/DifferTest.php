<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $pathJson1;
    private string $pathJson2;
    private string $pathYml1;
    private string $pathYaml2;

    private string $fileDiffStylish;
    private string $fileDiffPlain;
    private string $fileDiffJson;

    protected function setUp(): void
    {
        $this->pathJson1 = __DIR__ . '/fixtures/file1.json';
        $this->pathJson2 = __DIR__ . '/fixtures/file2.json';
        $this->pathYml1  = __DIR__ . '/fixtures/file1.yml';
        $this->pathYaml2 = __DIR__ . '/fixtures/file2.yaml';

        $this->fileDiffStylish = __DIR__ . '/fixtures/stylishDiff.txt';
        $this->fileDiffPlain   = __DIR__ . '/fixtures/plainDiff.txt';
        $this->fileDiffJson    = __DIR__ . '/fixtures/jsonDiff.json';
    }

    public function testGenDiff(): void
    {
        // Difference between two json - files with default formatter
        $this->assertStringEqualsFile(
            $this->fileDiffStylish,
            genDiff($this->pathJson1, $this->pathJson2)
        );

        // Difference between two json - files with 'plain' formatter
        $this->assertStringEqualsFile(
            $this->fileDiffPlain,
            genDiff($this->pathJson1, $this->pathJson2, 'plain')
        );

        // Difference between two json - files with 'stylish' formatter
        $this->assertStringEqualsFile(
            $this->fileDiffStylish,
            genDiff($this->pathJson1, $this->pathJson2, 'stylish')
        );

        // Difference between json & yaml - files with 'stylish' formatter
        $this->assertStringEqualsFile(
            $this->fileDiffStylish,
            genDiff($this->pathJson1, $this->pathYaml2, 'stylish')
        );

        // Difference between yml & yaml - files with 'stylish' formatter
        $this->assertStringEqualsFile(
            $this->fileDiffStylish,
            genDiff($this->pathYml1, $this->pathYaml2, 'stylish')
        );

        // Difference between yml & yaml - files with 'plain' formatter
        $this->assertStringEqualsFile(
            $this->fileDiffPlain,
            genDiff($this->pathYml1, $this->pathYaml2, 'plain')
        );

        // Difference between two json - files with 'json-formatter'
        $this->assertStringEqualsFile(
            $this->fileDiffJson,
            genDiff($this->pathJson1, $this->pathJson2, 'json')
        );

        // Difference between yml & yaml - files with 'json-formatter'
        $this->assertStringEqualsFile(
            $this->fileDiffJson,
            genDiff($this->pathYml1, $this->pathYaml2, 'json')
        );
    }
}
