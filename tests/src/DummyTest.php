<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base for Derafu's Projects.
 *
 * Copyright (c) 2025 Esteban De La Fuente Rubio / Derafu <https://www.derafu.org>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\TestsFoundation;

use Derafu\Foundation\Installer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Installer::class)]
final class DummyTest extends TestCase
{
    public function testDummyAssertTrue(): void
    {
        $this->assertTrue(true);
    }
}
