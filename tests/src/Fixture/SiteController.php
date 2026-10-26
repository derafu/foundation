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

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Real endpoints used to verify the site's smoke-test diagnostics.
 */
final class SiteController
{
    /**
     * @return array{status: string}
     */
    public function status(): array
    {
        return ['status' => 'ok'];
    }

    public function failure(): ResponseInterface
    {
        return new Response(
            503,
            ['Content-Type' => 'text/html'],
            '<style>hidden style</style><script>hidden script</script><main>Readable failure &amp; details</main>'
        );
    }

    public function emptyFailure(): ResponseInterface
    {
        return new Response(503);
    }

    public function notFound(): ResponseInterface
    {
        return new Response(404, [], 'The fixture page was retired.');
    }
}
