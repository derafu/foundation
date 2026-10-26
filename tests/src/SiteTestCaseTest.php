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
use Derafu\Foundation\Testing\SiteTestCase;
use Derafu\TestsFoundation\Fixture\ProbeSiteCase;
use Derafu\TestsFoundation\Fixture\RootProjectSiteCase;
use Derafu\TestsFoundation\Fixture\Website;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\SkippedWithMessageException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Exercises the reusable checks themselves: a valid site passes, and defective
 * sites produce the assertion failures that consuming applications rely on.
 */
#[CoversClass(SiteTestCase::class)]
#[UsesClass(SiteKernel::class)]
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class SiteTestCaseTest extends TestCase
{
    private Website $website;

    private ProbeSiteCase $probe;

    private bool $prepared = false;

    private string|false $locale;

    private string $sessionPath;

    protected function setUp(): void
    {
        $this->website = new Website();
        $this->locale = getenv('APP_LOCALE');
        $sessionPath = session_save_path();
        $this->assertIsString($sessionPath);
        $this->sessionPath = $sessionPath;
        session_save_path($this->website->directory . '/var/sessions');
        ProbeSiteCase::useProject($this->website->directory);
        $this->probe = new ProbeSiteCase('thePagesOfTheSiteAnswer');
    }

    protected function tearDown(): void
    {
        if ($this->prepared) {
            $this->probe->finish();
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        unset($_SESSION);
        session_save_path($this->sessionPath);
        putenv($this->locale === false ? 'APP_LOCALE' : 'APP_LOCALE=' . $this->locale);
        $this->website->dispose();
    }

    private function prepare(): void
    {
        $this->probe->prepare();
        $this->prepared = true;
    }

    private function addRoute(string $name, string $path, string $method): void
    {
        $this->website->append('config/routes.yaml', "\n" . $name . ":\n    path: " . $path
            . "\n    handler: 'Derafu\\TestsFoundation\\Fixture\\SiteController::" . $method . "'\n");
    }

    private function assertContentCheckFails(string $message, string $detail): void
    {
        try {
            $this->probe->theLinksOfTheContentGoSomewhere();
            $this->fail('The invalid content link must be detected.');
        } catch (ExpectationFailedException $exception) {
            $this->assertStringContainsString($message, $exception->getMessage());
            $comparison = $exception->getComparisonFailure();
            $this->assertNotNull($comparison);
            $this->assertStringContainsString($detail, $comparison->toString());
        }
    }

    public function testAllInheritedChecksAcceptAWorkingSite(): void
    {
        $this->prepare();
        $this->assertStringContainsString('/docs/guide', $this->probe->request('/sitemap.xml')[1]);
        $this->probe->thePagesOfTheSiteAnswer();
        $this->probe->aPageThatDoesNotExistIsNotFound();
        $this->probe->theErrorOfAPageThatDoesNotExistIsThePageOfTheSite();
        $this->probe->theErrorPageIsNotAPageOfTheSite();
        $this->probe->theCodeInSrcIsMeasuredByCoverage();
        $this->probe->theLinksOfTheContentGoSomewhere();
        $this->probe->noRouteOfTheSiteFailsInTheServer();
        $this->probe->everyRouteNameUsedInTheTemplatesIsDefined();
    }

    public function testTheDefaultProjectIsTheComposerRootPackage(): void
    {
        $this->assertSame(realpath(dirname(__DIR__, 2)), RootProjectSiteCase::directory());
    }

    public function testDifferentProjectsDoNotReuseEachOthersKernel(): void
    {
        $other = new Website();
        $this->website->write('templates/pages/home.html.twig', '<h1>First site</h1>');
        $other->write('templates/pages/home.html.twig', '<h1>Second site</h1>');
        try {
            $this->prepare();
            $this->assertStringContainsString('First site', $this->probe->request('/')[1]);
            ProbeSiteCase::useProject($other->directory);
            ProbeSiteCase::setUpBeforeClass();
            $this->assertStringContainsString('Second site', $this->probe->request('/')[1]);
            ProbeSiteCase::useProject($this->website->directory);
            $this->assertStringContainsString('First site', $this->probe->request('/')[1]);
        } finally {
            $other->dispose();
        }
    }

    public function testLanguagesAreIndependentAndTheEnvironmentIsRestored(): void
    {
        $this->prepare();
        $this->assertStringContainsString('No route found for', $this->probe->request('/locale', 'en')[1]);
        $this->assertStringContainsString('No se encontró ninguna ruta', $this->probe->request('/locale', 'es')[1]);
        $this->assertStringContainsString('No route found for', $this->probe->request('/locale', 'en')[1]);
        $this->assertSame('en', getenv('APP_LOCALE'));
        $this->probe->finish();
        $this->prepared = false;
        $this->assertSame($this->locale, getenv('APP_LOCALE'));
    }

    public function testCleanupClosesAnActiveSession(): void
    {
        $this->prepare();
        session_start();
        $_SESSION['fixture'] = 'stored';
        $this->probe->finish();
        $this->prepared = false;

        $this->assertNotSame(PHP_SESSION_ACTIVE, session_status());
        $this->assertArrayNotHasKey('_SESSION', $GLOBALS);
    }

    public function testARequiredPageThatFailsIsDetected(): void
    {
        $this->addRoute('failure', '/failed', 'failure');
        $this->probe->options['paths'] = ['/failed'];
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('GET /failed');
        $this->probe->thePagesOfTheSiteAnswer();
    }

    public function testServerErrorsHaveReadableDiagnosticsWithoutScriptsOrStyles(): void
    {
        $this->addRoute('failure', '/failed', 'failure');
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('GET /failed: Readable failure & details');
        $this->probe->noRouteOfTheSiteFailsInTheServer();
    }

    public function testEmptyServerErrorsStillIdentifyTheirPath(): void
    {
        $this->addRoute('failure', '/empty-failure', 'emptyFailure');
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('GET /empty-failure');
        $this->probe->noRouteOfTheSiteFailsInTheServer();
    }

    public function testExcludedRoutesAreNotRequestedByTheGeneralSmokeCheck(): void
    {
        $this->addRoute('failure', '/failed', 'failure');
        $this->probe->options['excludedPaths'] = ['/failed'];
        $this->prepare();
        $this->probe->noRouteOfTheSiteFailsInTheServer();
        $this->assertSame(503, $this->probe->request('/failed')[0]);
    }

    public function testAConfigurableRequiredPathCanUseAController(): void
    {
        $this->probe->options['paths'] = ['/status'];
        $this->prepare();
        $this->probe->thePagesOfTheSiteAnswer();
        $this->assertStringContainsString('ok', $this->probe->request('/status')[1]);
    }

    public function testANameMissingFromTheRouterIsDetectedInATemplate(): void
    {
        $this->website->write('templates/unused.html.twig', "{{ path('undefined_route') }}");
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('undefined_route');
        $this->probe->everyRouteNameUsedInTheTemplatesIsDefined();
    }

    public function testADynamicRouteNameIsReportedAsUnverifiable(): void
    {
        $this->website->write('templates/unused.html.twig', '{{ path(route_name) }}');
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Names that can not be checked by reading');
        $this->probe->everyRouteNameUsedInTheTemplatesIsDefined();
    }

    public function testBrokenContentLinksAreDetected(): void
    {
        $this->website->append('resources/content/docs/guide.md', "\n[Broken](/gone)\n");
        $this->prepare();
        $this->assertContentCheckFails('Links to a page that the site does not serve', '/gone');
    }

    public function testMissingContentFragmentsAreDetected(): void
    {
        $this->website->append('resources/content/docs/guide.md', "\n[Missing fragment](/docs/index#absent)\n");
        $this->prepare();
        $this->assertContentCheckFails('Links to a fragment that the page does not have', '#absent');
    }

    public function testLinksToExportFormatsAreDetected(): void
    {
        $this->website->append('resources/content/docs/guide.md', "\n[JSON](/docs/index.json)\n");
        $this->prepare();
        $this->assertContentCheckFails('Links to a format of a page', '/docs/index.json');
    }

    public function testExplicitlyAllowedLinksAreNotReported(): void
    {
        $this->website->append('resources/content/docs/guide.md', "\n[Allowed example](/gone)\n");
        $this->probe->options['allowedLinksInContent'] = ['/docs/guide' => ['/gone']];
        $this->prepare();
        $this->probe->theLinksOfTheContentGoSomewhere();
        $this->assertSame(404, $this->probe->request('/gone')[0]);
    }

    public function testAContentSelectorLimitsTheAuditedLinks(): void
    {
        $this->website->append('resources/content/docs/guide.md', "\n<div class=\"only-this\"><a href=\"/docs/index\">Valid</a></div>\n<a href=\"/gone\">Outside selector</a>\n");
        $this->probe->options['contentSelector'] = '.only-this';
        $this->prepare();
        $this->probe->theLinksOfTheContentGoSomewhere();
        $this->assertStringContainsString('Outside selector', $this->probe->request('/docs/guide')[1]);
    }

    public function testFragmentChecksCanBeDisabledForAPageRenderedByJavascript(): void
    {
        $this->website->append('resources/content/docs/guide.md', "\n[Dynamic fragment](/docs/index#client-rendered)\n");
        $this->probe->options['pagesWithoutFragmentChecksInContent'] = ['/docs/index'];
        $this->prepare();
        $this->probe->theLinksOfTheContentGoSomewhere();
        $this->assertStringNotContainsString('id="client-rendered"', $this->probe->request('/docs/index')[1]);
    }

    public function testAnUnreachableSitemapEntryIsDetected(): void
    {
        $this->addRoute('retired_guide', '/docs/guide', 'notFound');
        $this->prepare();
        $this->assertContentCheckFails('Pages of the sitemap that are not served', '/docs/guide');
    }

    public function testSitesWithoutContentSkipTheContentAudit(): void
    {
        $this->website->remove('resources/content');
        $this->prepare();
        try {
            $this->probe->theLinksOfTheContentGoSomewhere();
            $this->fail('The content check must be skipped for a site without content.');
        } catch (SkippedWithMessageException $exception) {
            $this->assertStringContainsString('The site has no content', $exception->getMessage());
        }
    }

    public function testSitesWithCodeRequireACoverageConfiguration(): void
    {
        $this->website->remove('phpunit.xml');
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('The site has no phpunit.xml');
        $this->probe->theCodeInSrcIsMeasuredByCoverage();
    }

    public function testACommentedReportIsNotAnActiveCoverageReport(): void
    {
        $this->website->write('phpunit.xml', '<phpunit><!-- <coverage><report><clover outputFile="var/coverage.xml"/></report></coverage> --></phpunit>');
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('no active <coverage>');
        $this->probe->theCodeInSrcIsMeasuredByCoverage();
    }

    public function testADistConfigurationCanSupplyTheCoverageReport(): void
    {
        (new Filesystem())->rename($this->website->directory . '/phpunit.xml', $this->website->directory . '/phpunit.xml.dist');
        $this->prepare();
        $this->probe->theCodeInSrcIsMeasuredByCoverage();
    }

    public function testANonPhpDirectoryNeedsNoCoverageReport(): void
    {
        $this->website->remove('src/SiteController.php');
        $this->website->write('src/notes.txt', 'The site has no application PHP code.');
        $this->website->remove('phpunit.xml');
        $this->prepare();
        $this->probe->theCodeInSrcIsMeasuredByCoverage();
        $this->assertFileExists($this->website->directory . '/src/notes.txt');
    }

    public function testASiteWithNoSrcDirectoryNeedsNoCoverageReport(): void
    {
        $this->website->remove('src');
        $this->website->remove('phpunit.xml');
        $this->prepare();
        $this->probe->theCodeInSrcIsMeasuredByCoverage();
        $this->assertDirectoryDoesNotExist($this->website->directory . '/src');
    }

    public function testASiteWithoutGetRoutesIsReported(): void
    {
        $this->website->write('config/routes.yaml', <<<'YAML'
submit:
    path: /submit
    methods: [POST]
    handler: 'Derafu\TestsFoundation\Fixture\SiteController::status'
item:
    path: /item/{id}
    handler: 'Derafu\TestsFoundation\Fixture\SiteController::status'
YAML);
        $this->prepare();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('The site has no routes that can be asked');
        $this->probe->noRouteOfTheSiteFailsInTheServer();
    }
}
