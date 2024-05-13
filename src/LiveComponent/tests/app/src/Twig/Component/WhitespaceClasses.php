<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Component;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

/**
 * @author Alexander Hofbauer <alex@derhofbauer.at>
 */
#[AsLiveComponent('whitespace_classes')]
final class WhitespaceClasses
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $class = 'third';

    #[LiveProp]
    public bool $changed = false;

    #[LiveAction]
    public function click(): void
    {
        $this->class = 'new';
        $this->changed = true;
    }
}
