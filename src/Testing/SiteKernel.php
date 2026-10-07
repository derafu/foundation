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
use Derafu\Renderer\Contract\RendererInterface;
use Derafu\Routing\Contract\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * The kernel of a site, as the tests of `SiteTestCase` use it: the same one,
 * that also tells which routes the site has and gives its router and its
 * renderer.
 *
 * The application never takes services out of the container, so the kernel
 * keeps it protected and its services private. Here it is the test that has to
 * look at them: it is reached by inheritance, and the services it needs get a
 * public alias so they are not removed when the container is compiled.
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

    /**
     * The router of the site.
     */
    public function router(): RouterInterface
    {
        $router = $this->getContainer()->get('test.router');
        assert($router instanceof RouterInterface);

        return $router;
    }

    /**
     * The renderer of the site.
     */
    public function renderer(): RendererInterface
    {
        $renderer = $this->getContainer()->get('test.renderer');
        assert($renderer instanceof RendererInterface);

        return $renderer;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(
        ContainerConfigurator $configurator,
        ContainerBuilder $container
    ): void {
        parent::configure($configurator, $container);

        $services = $configurator->services();
        $services->alias('test.router', RouterInterface::class)->public();
        $services->alias('test.renderer', RendererInterface::class)->public();
    }
}
