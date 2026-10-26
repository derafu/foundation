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

use Derafu\Kernel\Environment;
use Derafu\Renderer\Contract\RendererInterface;
use Derafu\Routing\Contract\RouteRegistrarInterface;
use Derafu\Routing\Contract\RouterInterface;
use Derafu\Routing\Contract\UrlGeneratorInterface;
use Derafu\Routing\Router;
use Derafu\Routing\ValueObject\RequestContext;
use Derafu\TestsFoundation\Fixture\FoundationKernel;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;

/**
 * Loads the complete Foundation configuration in a Composer-shaped installation.
 * The container constructs the actual Router, including its optional dependencies.
 */
#[CoversNothing]
final class ServicesTest extends TestCase
{
    private string $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = sys_get_temp_dir() . '/derafu-foundation-' . bin2hex(random_bytes(8));
        $filesystem = new Filesystem();
        $filesystem->mkdir([
            $this->projectDir . '/vendor/derafu/foundation/resources/config',
            $this->projectDir . '/templates/pages',
            $this->projectDir . '/config',
            $this->projectDir . '/var/cache',
        ]);

        $packageDir = dirname(__DIR__, 2);
        foreach (glob($packageDir . '/resources/config/*.yaml') ?: [] as $configuration) {
            $filesystem->symlink($configuration, $this->projectDir . '/vendor/derafu/foundation/resources/config/' . basename($configuration));
        }
        foreach (glob($packageDir . '/vendor/derafu/*', GLOB_ONLYDIR) ?: [] as $dependency) {
            $filesystem->symlink($dependency, $this->projectDir . '/vendor/derafu/' . basename($dependency));
        }
        $filesystem->dumpFile($this->projectDir . '/templates/pages/hello.html.twig', 'Hello from the filesystem');
        $filesystem->dumpFile($this->projectDir . '/config/services.yaml', <<<'YAML'
imports:
    - { resource: '../vendor/derafu/foundation/resources/config/foundation-services.yaml' }
YAML);
        $filesystem->dumpFile($this->projectDir . '/config/routes.yaml', <<<'YAML'
home:
    path: /
    handler: '%kernel.project_dir%/templates/pages/hello.html.twig'
YAML);
        $filesystem->dumpFile($this->projectDir . '/templates/services.html.twig', <<<'TWIG'
<a href="{{ path('home') }}">Home</a>
{{ 'No route found for "{uri}".'|trans({'uri': '/missing'}, 'errors', 'es') }}
<twig:block-alert type="success" content="Tagged component" icon="" />
TWIG);
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->projectDir);
    }

    private function container(): ContainerInterface
    {
        $environment = new Environment('test', true, ['PROJECT_DIR' => $this->projectDir]);
        return (new FoundationKernel($environment))->containerForTests();
    }

    public function testFoundationConstructsTheRouterAndItsInternalGenerator(): void
    {
        $container = $this->container();
        $router = $container->get('test.' . RouterInterface::class);
        $this->assertInstanceOf(Router::class, $router);

        $this->assertSame($router, $container->get('test.' . UrlGeneratorInterface::class));
        $this->assertSame($router, $container->get('test.' . RouteRegistrarInterface::class));
        $this->assertSame('/', $router->generate('home'));
        $this->assertSame('home', $router->match('/', 'GET')->getName());
        $this->assertSame(
            realpath($this->projectDir . '/templates/pages/hello.html.twig'),
            $router->match('/hello', 'GET')->getHandler(),
        );
    }

    public function testThePublicCapabilitiesUseTheSameRoutesAsTheRouter(): void
    {
        $container = $this->container();
        $registrar = $container->get('test.' . RouteRegistrarInterface::class);
        $generator = $container->get('test.' . UrlGeneratorInterface::class);
        $router = $container->get('test.' . RouterInterface::class);
        $this->assertInstanceOf(RouteRegistrarInterface::class, $registrar);
        $this->assertInstanceOf(UrlGeneratorInterface::class, $generator);
        $this->assertInstanceOf(RouterInterface::class, $router);

        $registrar->addRoute('document', '/docs/{doc:.+}', 'DocsController@show', methods: ['GET']);
        $generator->setContext(new RequestContext(baseUrl: '/app', host: 'example.test', scheme: 'https'));

        $this->assertSame('/app/docs/hello%20world', $generator->generate('document', ['doc' => 'hello world']));
        $this->assertSame(['doc' => 'hello world'], $router->match('/docs/hello%20world', 'GET')->getParameters());
    }

    public function testFoundationConstructsItsDeclaredInfrastructureAndFactoryAliases(): void
    {
        $container = $this->container();
        foreach ([
            RequestHandlerInterface::class, ClientInterface::class, CacheItemPoolInterface::class,
        ] as $interface) {
            $this->assertInstanceOf($interface, $container->get('test.' . $interface));
        }
        $client = $container->get('test.' . ClientInterface::class);
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame(10, $client->getConfig('timeout'));
        $cache = $container->get('test.' . CacheItemPoolInterface::class);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $cache);
        $this->assertTrue($cache->save($cache->getItem('foundation-services')->set('stored')));
        $this->assertSame('stored', $cache->getItem('foundation-services')->get());
        $factory = $container->get('test.' . HttpFactory::class);
        $this->assertInstanceOf(HttpFactory::class, $factory);
        $this->assertSame($factory, $container->get('test.' . RequestFactoryInterface::class));
        $this->assertSame($factory, $container->get('test.' . StreamFactoryInterface::class));
        $this->assertSame('/health', $factory->createRequest('GET', '/health')->getUri()->getPath());
        $this->assertSame('body', (string) $factory->createStream('body'));
    }

    public function testTheTranslatorAndRendererConsumeTheImportedProviderAndComponentTags(): void
    {
        $container = $this->container();
        $translator = $container->get('test.' . Translator::class);
        $this->assertInstanceOf(Translator::class, $translator);
        $this->assertSame(
            'No se encontró ninguna ruta para "/missing".',
            $translator->trans('No route found for "{uri}".', ['uri' => '/missing'], 'errors', 'es'),
        );

        $renderer = $container->get('test.' . RendererInterface::class);
        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $html = $renderer->render('services.html.twig');
        $this->assertStringContainsString('href="/"', $html);
        $this->assertStringContainsString('alert-success', $html);
        $this->assertStringContainsString('Tagged component', $html);
        $this->assertStringContainsString('No se encontró ninguna ruta para', $html);
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testTheConfiguredMiddlewarePipelineServesARealPage(): void
    {
        $sessionPath = $this->projectDir . '/var/sessions';
        (new Filesystem())->mkdir($sessionPath);
        $previousPath = session_save_path();
        $this->assertIsString($previousPath);
        session_save_path($sessionPath);

        try {
            $handler = $this->container()->get('test.' . RequestHandlerInterface::class);
            $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
            $response = $handler->handle(new ServerRequest('GET', 'https://example.test/', [], null, '1.1', [
                'REMOTE_ADDR' => '127.0.0.1',
            ]));

            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame('Hello from the filesystem', (string) $response->getBody());
        } finally {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }
            unset($_SESSION);
            session_save_path($previousPath);
        }
    }
}
