<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base of dependencies for Derafu's web applications.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\Foundation\Bridge\Twig;

use Derafu\Routing\Contract\RouterInterface;
use Derafu\Twig\Contract\ExtensionProviderInterface;
use Derafu\Twig\Extension\RoutingExtension;
use Derafu\Twig\Extension\TwigExtension;

/**
 * Adds standalone site routing functions and the context-aware twig filter.
 *
 * Other packages contribute their own extensions to the same Twig environment.
 */
final class FoundationExtensionProvider implements ExtensionProviderInterface
{
    /**
     * @param RouterInterface $router Shared router used to match site requests and generate URLs.
     */
    public function __construct(private readonly RouterInterface $router)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions(array $options = []): iterable
    {
        yield new RoutingExtension($this->router);
        yield new TwigExtension();
    }
}
