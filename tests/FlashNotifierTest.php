<?php

/*
 * Part of the Alerts package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Alerts
 * @version    5.1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Cartalyst\Alerts\Notifiers\FlashNotifier;
use Cartalyst\Alerts\Storage\IlluminateSession;

class FlashNotifierTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->session = m::mock(IlluminateSession::class);

        $this->notifier = new FlashNotifier('flash', [], $this->session);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_flash_an_alert()
    {
        $this->session->shouldReceive('flash')->with('cartalyst.alerts', m::any())->once();

        $this->session->shouldReceive('get')->with('cartalyst.alerts', [])->once();

        $this->notifier->alert('foo', 'form');

        $this->assertEmpty($this->notifier->get());
    }
}
