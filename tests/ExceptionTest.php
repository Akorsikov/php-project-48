<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class ExceptionTest extends TestCase
{
    public function testException(): void
    {
        $path1 = './tests/fixtures/file1.txt';
        $path2 = './tests/fixtures/file2.json';
        $message = "Error: Invalid file extension, use json- or yaml/yml- files!\n";

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);

        genDiff($path1, $path2, 'stylish');
    }

    public function testException2(): void
    {
        $path1 = './tests/fixtures/file1.yml';
        $path2 = './tests/fixtures/file2.txt';
        $message = "Error: The file '{$path2}' do not exist or are unreadable!\n";

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);

        genDiff($path1, $path2, 'stylish');
    }
}
