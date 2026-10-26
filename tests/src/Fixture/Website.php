<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base of dependencies for Derafu's web applications.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\TestsFoundation\Fixture;

use Symfony\Component\Filesystem\Filesystem;

/**
 * A disposable site backed by the actual Foundation YAML and installed packages.
 */
final class Website
{
    public readonly string $directory;

    public function __construct()
    {
        $this->directory = sys_get_temp_dir() . '/derafu-foundation-site-' . bin2hex(random_bytes(8));
        $filesystem = new Filesystem();
        $package = dirname(__DIR__, 3);
        $filesystem->mirror($package . '/tests/fixtures/site', $this->directory);
        $filesystem->mkdir([
            $this->directory . '/vendor/derafu/foundation/resources/config',
            $this->directory . '/src',
            $this->directory . '/var/sessions',
        ]);
        foreach (glob($package . '/resources/config/*.yaml') ?: [] as $configuration) {
            $filesystem->symlink($configuration, $this->directory . '/vendor/derafu/foundation/resources/config/' . basename($configuration));
        }
        foreach (glob($package . '/vendor/derafu/*', GLOB_ONLYDIR) ?: [] as $dependency) {
            $filesystem->symlink($dependency, $this->directory . '/vendor/derafu/' . basename($dependency));
        }
        $filesystem->symlink(__DIR__ . '/SiteController.php', $this->directory . '/src/SiteController.php');
    }

    public function write(string $path, string $content): void
    {
        (new Filesystem())->dumpFile($this->directory . '/' . $path, $content);
    }

    public function append(string $path, string $content): void
    {
        (new Filesystem())->appendToFile($this->directory . '/' . $path, $content);
    }

    public function remove(string $path): void
    {
        (new Filesystem())->remove($this->directory . '/' . $path);
    }

    public function dispose(): void
    {
        (new Filesystem())->remove($this->directory);
    }
}
