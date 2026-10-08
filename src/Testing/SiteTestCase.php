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
use Derafu\Renderer\Engine\Html\TwigHtmlEngine;
use Derafu\Twig\Lint\RouteReferenceScanner;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

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
 *   - The templates of the site only refer to routes that the site defines.
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
     * Builds the kernel of the site before the first test.
     *
     * The kernel reads the `.env` of the site when it boots, and writes its
     * variables to `$_ENV` and `$_SERVER`. If that happened inside a test,
     * PHPUnit would see a change of the global state in it and the test would
     * be risky; and restoring the variables afterwards would take them away
     * from the kernel, that reads some of them later (the arguments of the
     * services that are created when a page needs them). Built here, before
     * PHPUnit takes its photo of the state, they are part of it for all the
     * tests, and the kernel keeps seeing them.
     *
     * A class that overrides this method has to call the parent.
     */
    public static function setUpBeforeClass(): void
    {
        $locale = getenv('APP_LOCALE');

        self::useLocale(null);
        self::$kernels[''] ??= self::createKernel();
        self::$kernels['']->boot();

        self::useLocale($locale);
    }

    protected function setUp(): void
    {
        $this->locale = getenv('APP_LOCALE');
    }

    protected function tearDown(): void
    {
        // The session of the requests is not part of the state of the tests.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        unset($_SESSION);

        self::useLocale($this->locale);
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
        $response = $this->respond($path, $locale);

        return [$response->getStatusCode(), (string) $response->getBody()];
    }

    /**
     * Asks the site for a page, as a browser does: the whole response.
     *
     * @param string $path The path, like `/contact`.
     * @param string|null $locale The language of the site (`APP_LOCALE`).
     */
    private function respond(string $path, ?string $locale = null): ResponseInterface
    {
        return $this->kernel($locale)->handle(new ServerRequest(
            'GET',
            'http://localhost/' . ltrim($path, '/'),
            ['Accept' => 'text/html,application/xhtml+xml'],
            null,
            '1.1',
            ['SERVER_PORT' => 80, 'SERVER_NAME' => 'localhost', 'REQUEST_SCHEME' => 'http', 'HTTP_HOST' => 'localhost']
        ));
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
    public function theErrorOfAPageThatDoesNotExistIsThePageOfTheSite(): void
    {
        $response = $this->respond('/this-page-does-not-exist');
        $body = (string) $response->getBody();

        $this->assertSame(404, $response->getStatusCode());
        // The text of the error (Markdown) is what is sent when the page of the
        // errors can not be rendered: a serious failure, not a missing page.
        $this->assertStringStartsWith('text/html', $response->getHeaderLine('Content-Type'), $body);
        $this->assertStringNotContainsString('# An Error Occurred', $body);
        $this->assertStringContainsString('404', $body);
    }

    #[Test]
    public function theErrorPageIsNotAPageOfTheSite(): void
    {
        // It is the answer to an error, not something to ask for.
        foreach (['/error', '/error404'] as $path) {
            $this->assertSame(404, $this->get($path)[0], $path);
        }
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

    #[Test]
    public function everyRouteNameUsedInTheTemplatesIsDefined(): void
    {
        $kernel = $this->kernel(null);

        $engine = $kernel->renderer()->getEngine('twig');
        $this->assertInstanceOf(TwigHtmlEngine::class, $engine);

        $references = (new RouteReferenceScanner($engine->getTwig()))
            ->scanDirectory(self::projectDir() . '/templates')
        ;

        $missing = [];
        $dynamic = [];
        foreach ($references as $reference) {
            if ($reference->isDynamic()) {
                $dynamic[] = sprintf('%s:%d %s()', $reference->template, $reference->line, $reference->function);
            } elseif (!$kernel->router()->has((string) $reference->name)) {
                $missing[] = sprintf('%s:%d %s(\'%s\')', $reference->template, $reference->line, $reference->function, $reference->name);
            }
        }

        $this->assertSame([], $missing, "Routes that do not exist:\n" . implode("\n", $missing));
        $this->assertSame([], $dynamic, "Names that can not be checked by reading:\n" . implode("\n", $dynamic));
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
        // The language is read from the environment when the kernel needs it,
        // not only when it is built, so it is set on every call.
        self::useLocale($locale);

        return self::$kernels[(string) $locale] ??= self::createKernel();
    }

    /**
     * Sets the language of the site (`APP_LOCALE`) in the environment, or
     * removes it when there is none (`null` or `false`).
     */
    private static function useLocale(string|false|null $locale): void
    {
        putenv($locale === null || $locale === false ? 'APP_LOCALE' : 'APP_LOCALE=' . $locale);
    }

    private static function projectDir(): string
    {
        return (string) realpath(InstalledVersions::getRootPackage()['install_path']);
    }

    private static function createKernel(): SiteKernel
    {
        // Debug mode, so the container is built again with the configuration
        // of the moment and not taken from a cache that an earlier run left.
        return new SiteKernel(new Environment('test', true, [
            'APP_ENV' => 'test',
            'APP_DEBUG' => true,
            'PROJECT_DIR' => self::projectDir(),
            'URL_HOST' => 'localhost',
        ]));
    }
}
