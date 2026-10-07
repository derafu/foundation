<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base of dependencies for Derafu's web applications.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Foundation\Testing;

use Derafu\Http\Kernel;

/**
 * The kernel of a site, as the tests of `SiteTestCase` use it: the same one,
 * that also tells which routes the site has.
 */
final class SiteKernel extends Kernel
{
    /**
     * The routes of the site, as they are in the container.
     *
     * @return array<string, array{path: string, methods?: list<string>}>
     */
    public function routes(): array
    {
        /** @var array<string, array{path: string, methods?: list<string>}> */
        return $this->getContainer()->getParameter('routes');
    }
}
