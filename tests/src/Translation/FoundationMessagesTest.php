<?php

declare(strict_types=1);

/**
 * Derafu: Foundation - Base for Derafu's Projects.
 *
 * Copyright (c) 2026 Esteban De La Fuente Rubio / Derafu <https://www.derafu.dev>
 * Licensed under the MIT License.
 * See LICENSE file for more details.
 */

namespace Derafu\TestsFoundation\Translation;

use Derafu\Foundation\Installer;
use Derafu\Translation\Contract\TranslationResourceProviderInterface;
use Derafu\Twig\Lint\TwigTranslationAudit;
use Derafu\Twig\Service\TwigService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * The templates of the skeleton write no text that goes to the users, except the
 * ones that are declared here, which are in English on purpose:
 *
 *   - The labels of the debug page of the errors (`error.html.twig`). It is only
 *     shown with `context.error.debug`, to whoever develops the application,
 *     like the output of a command: it is technical, and it is not translated.
 *   - The titles by default of the layouts. They are placeholders that every
 *     application replaces.
 *
 * The package has no messages (it throws no exceptions), so it has no catalogue
 * of translations. A new text in a template fails here, instead of being left
 * out of the translation without anyone noticing.
 */
#[CoversClass(Installer::class)]
final class FoundationMessagesTest extends TestCase
{
    public function testTheTemplatesWriteNoTextThatIsNotDeclared(): void
    {
        $root = dirname(__DIR__, 3);

        // The package has no catalogue: it has no directory of translations.
        $withoutCatalogue = new class () implements TranslationResourceProviderInterface {
            public function getDirectories(): iterable
            {
                return [];
            }
        };

        $report = (new TwigTranslationAudit())->audit(
            $root . '/src',
            $root . '/installer/files/templates',
            $withoutCatalogue,
            (new TwigService([
                'extra' => false,
                'paths' => [$root . '/installer/files/templates'],
            ]))->getTwig(),
            allowedTexts: [
                // Debug page of the errors.
                'Technical Details',
                'Reference URI:',
                'File:',
                'Request URI:',
                'Timestamp:',
                'Environment:',
                'Stack Trace',
                'Previous Exception',
                // Titles by default, to be replaced by every application.
                "Derafu: Foundation - Base for Derafu's Projects",
                'Derafu: Project - Slogan',
            ]
        );

        $this->assertSame([], $report->describe($report->untranslatedTexts));
        $this->assertSame([], $report->describe($report->dynamicMessages));
        $this->assertSame([], $report->describe($report->missingTranslations));
        $this->assertSame([], $report->describe($report->notTranslatable));
    }
}
