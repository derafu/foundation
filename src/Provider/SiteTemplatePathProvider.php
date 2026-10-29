<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base of dependencies for Derafu's web applications.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Foundation\Provider;

use Derafu\Twig\Contract\TemplatePathProviderInterface;

/**
 * Contributes the site's templates directory before package fallback templates.
 *
 * Foundation registers this provider at priority 300. Additional domain providers
 * can contribute their own roots without rebuilding the site's renderer.
 */
final class SiteTemplatePathProvider implements TemplatePathProviderInterface
{
    /**
     * @param string $directory Absolute path to the site's templates directory.
     */
    public function __construct(private readonly string $directory)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getPaths(): array
    {
        return [$this->directory];
    }
}
