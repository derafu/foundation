<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base for Derafu's Projects.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Foundation;

use Composer\Script\Event;

/**
 * Installer class to handle file copying during installation.
 */
final class Installer
{
    private const FILES = [
        '.github/workflows/ci.yml',
        '.github/workflows/website.yml',
        'app/bootstrap.php' => true,
        'assets/css/app.css',
        'assets/img/.empty',
        'assets/js/app.js',
        'assets/js/images.js' => true,
        'config/routes.yaml',
        'config/services.yaml',
        'public/index.php' => true,
        'templates/components/footer.html.twig',
        'templates/components/header.html.twig',
        'templates/layouts/default.html.twig',
        'templates/pages/.empty',
        'templates/base.html.twig' => true,
        'templates/error.html.twig' => true,
        'templates/html.html.twig' => true,
        '.gitignore',
        'LICENSE',
        'package.json',
        'php-cs-fixer.php' => true,
        'phpstan.neon' => true,
        'phpunit.xml',
        'README.md',
        'vite.config.js' => true,
    ];

    /**
     * Copies files to their destinations during composer install/update.
     *
     * @param Event $event The composer event
     * @return void
     */
    public static function copyFiles(Event $event): void
    {
        $io = $event->getIO();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $foundationDir = $vendorDir . '/derafu/foundation/installer/files';

        if (!is_dir($foundationDir)) {
            return;
        }

        $projectDir = dirname($vendorDir);

        foreach (self::FILES as $key => $value) {
            if (is_bool($value)) {
                $file = $key;
                $overwrite = $value;
            } else {
                $file = $value;
                $overwrite = false;
            }

            $source = $foundationDir . '/' . $file;
            $destination = $projectDir . '/' . $file;

            // Check if destination file already exists.
            if (!$overwrite && file_exists($destination)) {
                //$io->write("  - <info>Skipping</info>: {$file} (already exists).");
                continue;
            }

            // Create destination directory if it doesn't exist.
            $destinationDir = dirname($destination);
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true);
                $io->write("  - <info>Created directory</info>: " .
                    substr($destinationDir, strlen($projectDir) + 1));
            }

            // Copy file.
            if (copy($source, $destination)) {
                $io->write("  - <info>Copied</info>: {$file}");
            } else {
                $io->write("  - <error>Failed to copy</error>: {$file}");
            }
        }
    }
}
