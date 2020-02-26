<?php

declare(strict_types=1);

namespace Klitsche\Dog;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\Dog\ConfigFile
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

        $configFile = new ConfigFile($this->tmpfile);
        $config = $configFile->getConfig();

        $this->assertSame('title', $config->getTitle());
    }

    public function testGetConfigWithRelativePath(): void
    {
        $this->tmpfile = tempnam(getcwd(), 'dogtest');
        file_put_contents($this->tmpfile, 'title: title');

        $configFile = new ConfigFile(basename($this->tmpfile));
        $config = $configFile->getConfig();

        $this->assertSame('title', $config->getTitle());
    }

    public function testGetConfigWithInvalidFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ConfigFile('missingFile');
    }
}
