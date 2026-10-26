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

final class RootProjectSiteCase extends SiteTestCase
{
    public static function directory(): string
    {
        return parent::projectDir();
    }
}
