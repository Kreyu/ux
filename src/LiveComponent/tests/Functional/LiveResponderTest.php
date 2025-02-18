<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\LiveComponent\Tests\Functional\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\LiveComponent\Tests\LiveComponentTestHelper;
use Zenstruck\Browser\Test\HasBrowser;

/**
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
final class LiveResponderTest extends KernelTestCase
{
    use HasBrowser;
    use LiveComponentTestHelper;

    public function testComponentCanEmitEvents(): void
    {
        $component = $this->mountComponent('component_with_emit');
        $dehydrated = $this->dehydrateComponent($component);

        $this->browser()
            ->throwExceptions()
            ->post('/_components/component_with_emit/actionThatEmits', [
                'body' => json_encode(['props' => $dehydrated->getProps()]),
            ])
            ->assertSuccessful()
            ->assertSee('Event: event1')
            ->assertSee('Data: {"foo":"bar"}');
    }
}
