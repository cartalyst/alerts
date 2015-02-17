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
use Cartalyst\Alerts\Native\Facades\Alert;
use Cartalyst\Alerts\Native\AlertsBootstrapper;

class NativeAlertsTest extends PHPUnit_Framework_TestCase
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

        AlertsBootstrapper::setConfig(['default' => 'view']);

        $instance = Alert::instance();

        $this->alerts = $instance->getAlerts();
    }

    /** @test */
    public function it_can_retrieve_bootstrapper_config()
    {
        $this->assertSame(['default' => 'view'], AlertsBootstrapper::getConfig());
    }

    /** @test */
    public function it_can_instantiate_alerts()
    {
        $this->assertInstanceOf('Cartalyst\Alerts\Alerts', $this->alerts);
    }

    /** @test */
    public function it_can_set_and_retrieve_errors()
    {
        $this->alerts->error('foo');

        $this->assertEquals('foo', head($this->alerts->get())->message);
        $this->assertEquals('error', head($this->alerts->get())->type);
    }
}
