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

use Composer\InstalledVersions;
use Derafu\Kernel\Environment;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Smoke tests of a site: the kernel with the real configuration of `config/`,
 * a request, and the response with the templates rendered.
 *
 * A site extends it and, with that alone, gets the tests of what every site of
 * Derafu must do:
 *
 *   - The pages of `paths()` answer with a 200.
 *   - A page that does not exist answers with a 404.
 *   - No route of the site that can be asked without parameters answers with an
 *     error of the server (5xx), except the ones of `excludedPaths()`.
 *
 * The tests that are only about the site (its texts, its languages) are added
 * to the class of the site, that can use `get()` to ask for a page:
 *
 *     #[CoversNothing]
 *     final class WebsiteTest extends SiteTestCase
 *     {
 *     }
 *
 * The site needs `phpunit/phpunit`, that this package only suggests.
 */
#[CoversNothing]
abstract class SiteTestCase extends TestCase
{
    /**
     * Kernels already created, by language (a kernel is built with the
     * language of the moment, and building it is what takes time).
     *
     * @var array<string, SiteKernel>
     */
    private static array $kernels = [];

    private string|false $locale;

    /**
     * @var array<string, mixed>
     */
    private array $env;

    /**
     * @var array<string, mixed>
     */
    private array $server;

    protected function setUp(): void
    {
        $this->locale = getenv('APP_LOCALE');
        $this->env = $_ENV;
        $this->server = $_SERVER;
    }

    protected function tearDown(): void
    {
        // The session of the requests, and the variables that the kernel reads
        // from the `.env` of the site, are not part of the state of the tests.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        unset($_SESSION);
        $_ENV = $this->env;
        $_SERVER = $this->server;

        putenv($this->locale === false ? 'APP_LOCALE' : 'APP_LOCALE=' . $this->locale);
    }

    /**
     * The pages that must answer with a 200.
     *
     * A site without some of them (for example one that has no contact page)
     * overrides this method with its own list.
     *
     * @return list<string>
     */
    protected function paths(): array
    {
        return ['/', '/contact', '/contact/success'];
    }

    /**
     * The paths that the test of the routes does not ask for.
     *
     * A route that needs a service that is not there when testing (a game
     * server, a payment gateway) can not answer; the site lists it here, and
     * says why in a comment, instead of having a test that always fails.
     *
     * @return list<string>
     */
    protected function excludedPaths(): array
    {
        return [];
    }

    /**
     * Asks the site for a page.
     *
     * @param string $path The path, like `/contact`.
     * @param string|null $locale The language of the site (`APP_LOCALE`); the
     * one of the environment if it is null.
     * @return array{int, string} The status and the body of the response.
     */
    protected function get(string $path, ?string $locale = null): array
    {
        $response = $this->kernel($locale)->handle(new ServerRequest(
            'GET',
            'http://localhost/' . ltrim($path, '/'),
            [],
            null,
            '1.1',
            ['SERVER_PORT' => 80, 'SERVER_NAME' => 'localhost', 'REQUEST_SCHEME' => 'http', 'HTTP_HOST' => 'localhost']
        ));

        return [$response->getStatusCode(), (string) $response->getBody()];
    }

    #[Test]
    public function thePagesOfTheSiteAnswer(): void
    {
        foreach ($this->paths() as $path) {
            [$status, $body] = $this->get($path);

            $this->assertSame(200, $status, 'GET ' . $path);
            $this->assertStringNotContainsString('Internal Server Error', $body, 'GET ' . $path);
        }
    }

    #[Test]
    public function aPageThatDoesNotExistIsNotFound(): void
    {
        [$status] = $this->get('/this-page-does-not-exist');

        $this->assertSame(404, $status);
    }

    #[Test]
    public function noRouteOfTheSiteFailsInTheServer(): void
    {
        $paths = $this->routePaths();

        $this->assertNotEmpty($paths, 'The site has no routes that can be asked.');
        foreach ($paths as $path) {
            [$status] = $this->get($path);

            $this->assertLessThan(500, $status, 'GET ' . $path);
        }
    }

    /**
     * The paths of the routes that a GET can ask, without parameters.
     *
     * @return list<string>
     */
    private function routePaths(): array
    {
        $paths = [];
        foreach ($this->kernel(null)->routes() as $route) {
            $methods = $route['methods'] ?? [];
            if (($methods !== [] && !in_array('GET', $methods, true)) || str_contains($route['path'], '{')) {
                continue;
            }
            $paths[] = '/' . ltrim($route['path'], '/');
        }

        return array_values(array_diff(array_unique($paths), $this->excludedPaths()));
    }

    /**
     * The kernel of the site, for the language.
     */
    private function kernel(?string $locale): SiteKernel
    {
        putenv($locale === null ? 'APP_LOCALE' : 'APP_LOCALE=' . $locale);

        return self::$kernels[(string) $locale] ??= $this->createKernel();
    }

    private function createKernel(): SiteKernel
    {
        $root = InstalledVersions::getRootPackage()['install_path'];

        // Debug mode, so the container is built again with the configuration
        // of the moment and not taken from a cache that an earlier run left.
        return new SiteKernel(new Environment('test', true, [
            'APP_ENV' => 'test',
            'APP_DEBUG' => true,
            'PROJECT_DIR' => realpath($root),
            'URL_HOST' => 'localhost',
        ]));
    }
}
