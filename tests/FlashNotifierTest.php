<?php

/**
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
 * @version    2.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Alerts\Notifiers\FlashNotifier;

class FlashNotifierTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Setup.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->session = m::mock('Cartalyst\Alerts\Storage\IlluminateSession');

        $this->notifier = new FlashNotifier('flash', [], $this->session);
    }

    /** @test */
    public function it_can_flash_an_alert()
    {
        $this->session->shouldReceive('flash')
            ->with('cartalyst.alerts', m::any())
            ->once();

        $this->notifier->alert('foo', 'form');
    }

    /** @test */
    public function it_can_retrieve_all_flash_messages()
    {
        $this->session->shouldReceive('get')
            ->with('cartalyst.alerts', [])
            ->once();

        $this->notifier->get();
    }
}
