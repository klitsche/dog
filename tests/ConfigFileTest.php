<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\Config
 */
class ConfigFileTest extends TestCase
{
    private $tmpfile;

    protected function tearDown(): void
    {
        if ($this->tmpfile !== null && file_exists($this->tmpfile)) {
            @unlink($this->tmpfile);
        }

        parent::tearDown();
    }

    public function testGetConfigWithAbsolutePath(): void
    {
        $this->tmpfile = tempnam(sys_get_temp_dir(), 'dogtest');
        file_put_contents($this->tmpfile, 'title: title');

        $config = Config::fromYamlFile($this->tmpfile, sys_get_temp_dir());

        $this->assertSame('title', $config->getTitle());
    }

    public function testGetConfigWithRelativePath(): void
    {
        $this->tmpfile = tempnam(sys_get_temp_dir(), 'dogtest');
        file_put_contents($this->tmpfile, 'title: title');

        $config = Config::fromYamlFile(basename($this->tmpfile), sys_get_temp_dir());

        $this->assertSame('title', $config->getTitle());
    }

    public function testGetConfigWithInvalidFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Config::fromYamlFile('missingfile', sys_get_temp_dir());
    }
}
