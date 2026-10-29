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

use Derafu\Form\Contract\Csrf\CsrfTokenManagerInterface;
use Derafu\Form\Contract\Renderer\FormRendererInterface;
use Derafu\Http\Contract\RequestStackInterface;
use Derafu\Http\Kernel;
use Derafu\Http\Middleware\StaticFilesMiddleware;
use Derafu\Renderer\Contract\RendererInterface;
use Derafu\Routing\Contract\RouteRegistrarInterface;
use Derafu\Routing\Contract\RouterInterface;
use Derafu\Routing\Contract\UrlGeneratorInterface;
use Derafu\Routing\Parser\DynamicParser;
use Derafu\Routing\Parser\FileSystemParser;
use Derafu\Routing\Parser\StaticParser;
use Derafu\Twig\Contract\TwigServiceInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Uses the real HTTP kernel setup and exposes Foundation's declared services
 * through test aliases, without replacing their definitions or constructors.
 */
final class FoundationKernel extends Kernel
{
    public function containerForTests(): ContainerInterface
    {
        return $this->getContainer();
    }

    protected function configure(ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        parent::configure($configurator, $container);

        foreach ([
            RouterInterface::class, UrlGeneratorInterface::class, RouteRegistrarInterface::class,
            RendererInterface::class, RequestHandlerInterface::class, TranslatorInterface::class,
            ClientInterface::class, CacheItemPoolInterface::class,
            RequestFactoryInterface::class, StreamFactoryInterface::class,
            TwigServiceInterface::class, FormRendererInterface::class,
            CsrfTokenManagerInterface::class, RequestStackInterface::class,
            StaticFilesMiddleware::class, StaticParser::class, DynamicParser::class, FileSystemParser::class,
        ] as $id) {
            $configurator->services()->alias('test.' . $id, $id)->public();
        }
    }
}
