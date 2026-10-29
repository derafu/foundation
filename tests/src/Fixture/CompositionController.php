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
use Derafu\Form\Contract\Factory\FormFactoryInterface;
use Derafu\Http\Contract\RequestInterface;
use Derafu\Renderer\Contract\RendererInterface;

/**
 * Real consumer endpoints for the shared session, form and rendering composition.
 */
final class CompositionController
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrf,
        private readonly FormFactoryInterface $forms,
        private readonly RendererInterface $renderer
    ) {
    }

    /**
     * @return array{session: mixed, flash: mixed, csrf: bool}
     */
    public function state(RequestInterface $request): array
    {
        $request->session()->set('foundation.marker', 'stored');
        $request->flash()->flashNow('notice', 'Shared flash');
        $token = $this->csrf->getToken('foundation');

        return [
            'session' => $request->session()->get('foundation.marker'),
            'flash' => $request->flash()->getFlash('notice'),
            'csrf' => $this->csrf->isValid('foundation', $token),
        ];
    }

    public function form(): string
    {
        $form = $this->forms->create([
            'data' => ['name' => 'Ada'],
            'options' => ['csrf_protection' => true, 'csrf_token_id' => 'foundation'],
        ]);

        return $this->renderer->renderFromString(
            '{{ form(form) }}<p>{{ app.route.name }}</p>',
            ['form' => $form]
        );
    }
}
