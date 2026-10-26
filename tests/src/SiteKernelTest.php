<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base of dependencies for Derafu's web applications.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\TestsFoundation;

use Derafu\Foundation\Testing\SiteKernel;
use Derafu\Kernel\Environment;
use Derafu\TestsFoundation\Fixture\Website;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKernel::class)]
final class SiteKernelTest extends TestCase
{
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testItExposesTheActualRoutesRouterAndRendererOfTheSite(): void
    {
        $website = new Website();
        $previous = session_save_path();
        $this->assertIsString($previous);
        session_save_path($website->directory . '/var/sessions');

        try {
            $kernel = new SiteKernel(new Environment('test', true, ['PROJECT_DIR' => $website->directory]));
            $kernel->boot();

            $routes = $kernel->routes();
            $this->assertSame('/', $routes['home']['path']);
            $this->assertSame('/docs/{doc:.+}', $routes['docs_doc']['path']);
            $this->assertSame('docs_doc', $kernel->router()->match('/docs/guide', 'GET')->getName());
            $this->assertSame('/docs/guide', $kernel->router()->generate('docs_doc', ['doc' => 'guide']));
            $this->assertStringContainsString('Fixture home', $kernel->renderer()->render('pages/home.html.twig'));

            $response = $kernel->handle(new ServerRequest('GET', 'http://localhost/'));
            $this->assertSame(200, $response->getStatusCode());
            $this->assertStringContainsString('Fixture home', (string) $response->getBody());
        } finally {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }
            unset($_SESSION);
            session_save_path($previous);
            $website->dispose();
        }
    }
}
