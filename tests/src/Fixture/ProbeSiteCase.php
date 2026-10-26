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

use Derafu\Foundation\Testing\SiteTestCase;

/**
 * Runs the inherited checks against a selected real site, with the same hooks
 * that a consuming application can override.
 */
final class ProbeSiteCase extends SiteTestCase
{
    private static string $directory;

    /**
     * @var array{paths?: list<string>, excludedPaths?: list<string>, allowedLinksInContent?: array<string, list<string>>, contentSelector?: string, pagesWithoutFragmentChecksInContent?: list<string>}
     */
    public array $options = [];

    public static function useProject(string $directory): void
    {
        self::$directory = $directory;
    }

    protected static function projectDir(): string
    {
        return self::$directory;
    }

    public function prepare(): void
    {
        static::setUpBeforeClass();
        parent::setUp();
    }

    public function finish(): void
    {
        parent::tearDown();
    }

    /** @return array{int, string} */
    public function request(string $path, ?string $locale = null): array
    {
        return $this->get($path, $locale);
    }

    protected function paths(): array
    {
        return $this->options['paths'] ?? parent::paths();
    }

    protected function excludedPaths(): array
    {
        return $this->options['excludedPaths'] ?? parent::excludedPaths();
    }

    protected function allowedLinksInContent(): array
    {
        return $this->options['allowedLinksInContent'] ?? parent::allowedLinksInContent();
    }

    protected function contentSelector(): string
    {
        return $this->options['contentSelector'] ?? parent::contentSelector();
    }

    protected function pagesWithoutFragmentChecksInContent(): array
    {
        return $this->options['pagesWithoutFragmentChecksInContent'] ?? parent::pagesWithoutFragmentChecksInContent();
    }
}
