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

use Derafu\Form\Contract\Csrf\CsrfTokenManagerInterface;
use Derafu\Form\Contract\Renderer\FormRendererInterface;
use Derafu\Foundation\Bridge\Twig\FoundationExtensionProvider;
use Derafu\Foundation\Provider\SiteTemplatePathProvider;
use Derafu\Http\Contract\RequestStackInterface;
use Derafu\Kernel\Environment;
use Derafu\Renderer\Contract\RendererInterface;
use Derafu\Renderer\Engine\Html\TwigHtmlEngine;
use Derafu\TestsFoundation\Fixture\FoundationKernel;
use Derafu\TestsFoundation\Fixture\Website;
use Derafu\Twig\Contract\TwigServiceInterface;
use LogicException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Exercise Foundation's production YAML through a compiled real-site container.
 */
#[CoversClass(SiteTemplatePathProvider::class)]
#[CoversClass(FoundationExtensionProvider::class)]
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class ServiceCompositionTest extends TestCase
{
    private Website $website;

    private string $sessionPath;

    protected function setUp(): void
    {
        $this->website = new Website();
        $previousPath = session_save_path();
        $this->assertIsString($previousPath);
        $this->sessionPath = $previousPath;
        session_save_path($this->website->directory . '/var/sessions');
        $this->website->append('config/services.yaml', <<<'YAML'

    Derafu\TestsFoundation\Fixture\CompositionController:
        autowire: true
        public: true
YAML);
        $this->website->append('config/routes.yaml', <<<'YAML'

composition_state:
    path: /composition/state
    handler: 'Derafu\TestsFoundation\Fixture\CompositionController::state'
composition_form:
    path: /composition/form
    handler: 'Derafu\TestsFoundation\Fixture\CompositionController::form'
YAML);
    }

    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        unset($_SESSION);
        session_save_path($this->sessionPath);
        $this->website->dispose();
    }

    private function container(): ContainerInterface
    {
        $environment = new Environment('test', true, ['PROJECT_DIR' => $this->website->directory]);

        return (new FoundationKernel($environment))->containerForTests();
    }

    private function renderer(ContainerInterface $container): RendererInterface
    {
        $renderer = $container->get('test.' . RendererInterface::class);
        $this->assertInstanceOf(RendererInterface::class, $renderer);

        return $renderer;
    }

    private function handler(ContainerInterface $container): RequestHandlerInterface
    {
        $handler = $container->get('test.' . RequestHandlerInterface::class);
        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);

        return $handler;
    }

    private function request(string $path): ServerRequest
    {
        return new ServerRequest('GET', 'https://example.test' . $path, [], null, '1.1', ['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function testSiteOverridesPackageTemplatesAndFormUsesTheSharedRendererAndTwig(): void
    {
        $this->website->write('templates/components/block-alert.html.twig', '<p>Site alert: {{ content }}</p>');
        $container = $this->container();
        $renderer = $this->renderer($container);
        $formRenderer = $container->get('test.' . FormRendererInterface::class);
        $twig = $container->get('test.' . TwigServiceInterface::class);
        $this->assertInstanceOf(FormRendererInterface::class, $formRenderer);
        $this->assertInstanceOf(TwigServiceInterface::class, $twig);
        $this->assertSame($renderer, $formRenderer->getRenderer());
        $engine = $renderer->getEngine('twig');
        $this->assertInstanceOf(TwigHtmlEngine::class, $engine);
        $this->assertSame($twig->getTwig(), $engine->getTwig());
        $this->assertStringContainsString(
            'Site alert: Shared component',
            $renderer->renderFromString('<twig:block-alert content="Shared component" />')
        );
        $this->assertSame('Hello Ada', $renderer->renderFromString("{{ 'Hello {{ name }}'|twig }}", ['name' => 'Ada']));
        $this->assertStringContainsString(
            'href="/"',
            $renderer->renderFromString('<a href="{{ path("home") }}">Home</a>')
        );
    }

    public function testConfiguredEnginesRenderMarkdownAndARealPdf(): void
    {
        $renderer = $this->renderer($this->container());
        $markdown = $renderer->renderFromString('# Foundation heading', options: [
            'engine' => 'markdown', 'wrapperTemplate' => false,
        ]);
        $this->assertStringContainsString('<h1', $markdown);
        $this->assertStringContainsString('Foundation heading', $markdown);
        $pdf = $renderer->renderFromString('<h1>Foundation PDF</h1>', options: ['engine' => 'pdf']);
        $this->assertStringStartsWith('%PDF-', $pdf);
        $this->assertStringContainsString('%%EOF', $pdf);
    }

    public function testPipelineSuppliesSessionFlashAndCsrfAndRendersAProtectedForm(): void
    {
        $container = $this->container();
        $handler = $this->handler($container);
        $response = $handler->handle($this->request('/composition/state'));
        $this->assertSame(200, $response->getStatusCode(), (string) $response->getBody());
        $this->assertSame([
            'session' => 'stored', 'flash' => 'Shared flash', 'csrf' => true,
        ], json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR));

        $form = $handler->handle($this->request('/composition/form'));
        $this->assertSame(200, $form->getStatusCode(), (string) $form->getBody());
        $html = (string) $form->getBody();
        $this->assertStringContainsString('name="name"', $html);
        $this->assertStringContainsString('value="Ada"', $html);
        $this->assertStringContainsString('name="_csrf_token"', $html);
        $this->assertStringContainsString('<p>composition_form</p>', $html);

        $requests = $container->get('test.' . RequestStackInterface::class);
        $this->assertInstanceOf(RequestStackInterface::class, $requests);
        $this->assertNull($requests->getCurrentRequest());
        $csrf = $container->get('test.' . CsrfTokenManagerInterface::class);
        $this->assertInstanceOf(CsrfTokenManagerInterface::class, $csrf);
        try {
            $csrf->getToken('outside-request');
            $this->fail('The CSRF manager must release the handled request session.');
        } catch (LogicException $exception) {
            $this->assertStringContainsString('There is no session for the CSRF tokens.', $exception->getMessage());
        }
    }

    public function testStaticFilesAndSiteErrorPagesUseFoundationConfiguration(): void
    {
        $this->website->write('public/static/site.css', 'body{color:blue}');
        $handler = $this->handler($this->container());
        $asset = $handler->handle($this->request('/site.css'));
        $this->assertSame(200, $asset->getStatusCode());
        $this->assertSame('body{color:blue}', (string) $asset->getBody());
        $this->assertStringStartsWith('text/css', $asset->getHeaderLine('Content-Type'));
        $error = $handler->handle($this->request('/missing')->withHeader('Accept', 'text/html'));
        $this->assertSame(404, $error->getStatusCode());
        $this->assertStringContainsString('<h1>Error 404</h1>', (string) $error->getBody());
        $home = $handler->handle($this->request('/'));
        $this->assertSame(200, $home->getStatusCode());
        $this->assertStringContainsString('Fixture home', (string) $home->getBody());
    }
}
