<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base for Derafu's Projects.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\TestsFoundation;

use Composer\Factory;
use Composer\IO\BufferIO;
use Composer\Script\Event;
use Composer\Util\Platform;
use Derafu\Foundation\Installer;
use FilesystemIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The installer copies the skeleton of the package to the project, with a real
 * Composer: the project is a temporary directory whose `vendor` has a copy of
 * the skeleton.
 */
#[CoversClass(Installer::class)]
final class InstallerTest extends TestCase
{
    /**
     * Files of the skeleton that the installer replaces on every run.
     */
    private const REPLACED = [
        'app/bootstrap.php',
        'assets/js/images.js',
        'public/index.php',
        'templates/base.html.twig',
        'templates/error.html.twig',
        'templates/html.html.twig',
        'php-cs-fixer.php',
        'phpstan.neon',
        'vite.config.js',
    ];

    /**
     * Variables of the environment that Composer sets when it is created (to
     * run git without asking and in English). The test puts them back, so they
     * are not a change of the global state that the test leaves.
     */
    private const COMPOSER_ENV = ['GIT_TERMINAL_PROMPT', 'LANGUAGE'];

    private string $project;

    private BufferIO $io;

    /**
     * @var array<string, string|false>
     */
    private array $env = [];

    protected function setUp(): void
    {
        $this->project = sys_get_temp_dir() . '/derafu-foundation-' . bin2hex(random_bytes(4));
        mkdir($this->project . '/vendor/derafu/foundation', 0777, true);
        $this->io = new BufferIO();
        foreach (self::COMPOSER_ENV as $name) {
            $this->env[$name] = Platform::getEnv($name);
        }
    }

    protected function tearDown(): void
    {
        $this->remove($this->project);
        foreach ($this->env as $name => $value) {
            if ($value === false) {
                Platform::clearEnv($name);
            } else {
                Platform::putEnv($name, $value);
            }
        }
    }

    private function skeleton(): string
    {
        return dirname(__DIR__, 2) . '/installer/files';
    }

    private function remove(string $path): void
    {
        if (!is_dir($path) || is_link($path)) {
            @unlink($path);

            return;
        }

        foreach (new FilesystemIterator($path) as $item) {
            $this->remove($item->getPathname());
        }
        rmdir($path);
    }

    /**
     * @return list<string> The files under a directory, relative to it.
     */
    private function files(string $directory, string $skip = ''): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            $relative = substr($file->getPathname(), strlen($directory) + 1);
            if ($skip === '' || !str_starts_with($relative, $skip)) {
                $files[] = $relative;
            }
        }
        sort($files);

        return $files;
    }

    private function installSkeleton(): void
    {
        $target = $this->project . '/vendor/derafu/foundation/installer/files';
        foreach ($this->files($this->skeleton()) as $file) {
            @mkdir(dirname($target . '/' . $file), 0777, true);
            copy($this->skeleton() . '/' . $file, $target . '/' . $file);
        }
    }

    private function write(string $file, string $content): void
    {
        @mkdir(dirname($this->project . '/' . $file), 0777, true);
        file_put_contents($this->project . '/' . $file, $content);
    }

    private function install(): void
    {
        $composer = Factory::create(
            $this->io,
            ['config' => ['vendor-dir' => $this->project . '/vendor']],
            false
        );

        Installer::copyFiles(new Event('post-install-cmd', $composer, $this->io));
    }

    #[Test]
    public function copiesTheWholeSkeletonToAnEmptyProject(): void
    {
        $this->installSkeleton();

        $this->install();

        // Every file of the skeleton is in the project, and nothing else.
        $this->assertSame(
            $this->files($this->skeleton()),
            $this->files($this->project, 'vendor/')
        );
        $this->assertStringContainsString('Created directory: app', $this->io->getOutput());
        $this->assertStringContainsString('Copied: app/bootstrap.php', $this->io->getOutput());
    }

    #[Test]
    public function doesNotOverwriteTheFilesThatTheProjectOwns(): void
    {
        $this->installSkeleton();
        $owned = array_diff($this->files($this->skeleton()), self::REPLACED);
        foreach ($owned as $file) {
            $this->write($file, 'mine');
        }

        $this->install();

        foreach ($owned as $file) {
            $this->assertSame('mine', file_get_contents($this->project . '/' . $file), $file);
            $this->assertStringNotContainsString('Copied: ' . $file . "\n", $this->io->getOutput(), $file);
        }
    }

    #[Test]
    public function replacesTheFilesOfTheSkeleton(): void
    {
        $this->installSkeleton();
        foreach (self::REPLACED as $file) {
            $this->write($file, 'old');
        }

        $this->install();

        foreach (self::REPLACED as $file) {
            $this->assertSame(
                file_get_contents($this->skeleton() . '/' . $file),
                file_get_contents($this->project . '/' . $file),
                $file
            );
        }
    }

    #[Test]
    public function doesNothingWhenThereIsNoSkeleton(): void
    {
        $this->install();

        $this->assertSame([], $this->files($this->project, 'vendor/'));
        $this->assertSame('', $this->io->getOutput());
    }

    #[Test]
    public function reportsTheFileThatCanNotBeCopied(): void
    {
        $this->installSkeleton();
        // A directory where a file of the skeleton goes, so it can not be written.
        mkdir($this->project . '/templates/error.html.twig', 0777, true);

        // PHP warns when it can not copy: the test collects the warning.
        $warnings = [];
        set_error_handler(function (int $level, string $message) use (&$warnings): bool {
            $warnings[] = $message;

            return true;
        });
        try {
            $this->install();
        } finally {
            restore_error_handler();
        }

        $this->assertStringContainsString('Failed to copy: templates/error.html.twig', $this->io->getOutput());
        $this->assertCount(1, $warnings);
        // The rest of the files are copied anyway.
        $this->assertFileExists($this->project . '/app/bootstrap.php');
    }
}
