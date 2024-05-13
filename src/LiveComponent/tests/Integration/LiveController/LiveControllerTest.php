<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\LiveComponent\Tests\Integration\LiveController;

use Symfony\Component\Panther\PantherTestCase;

/**
 * @author Alexander Hofbauer <alex@derhofbauer.at>
 */
class LiveControllerTest extends PantherTestCase
{
    public function testWhitespaceClasses(): void
    {
        $client = self::createPantherClient();
        $client->request('GET', '/whitespace-classes');
        $client->clickLink('Remove class and render');

        // wait required to run live_controller JS
        $client->waitFor('#changed', 10);

        self::assertSelectorAttributeContains('p#content', 'class', 'second new');
    }
}
