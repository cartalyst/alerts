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
 * @version    6.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2022, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use Mockery as m;
use Cartalyst\Alerts\Alerts;
use PHPUnit\Framework\TestCase;
use Cartalyst\Alerts\Native\Facades\Alert;
use Cartalyst\Alerts\Native\AlertsBootstrapper;

class NativeAlertsTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        AlertsBootstrapper::setConfig(['default' => 'view']);

        $instance = Alert::instance();

        $this->alerts = $instance->getAlerts();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_retrieve_bootstrapper_config()
    {
        $this->assertSame(['default' => 'view'], AlertsBootstrapper::getConfig());
    }

    /** @test */
    public function it_can_instantiate_alerts()
    {
        $this->assertInstanceOf(Alerts::class, $this->alerts);
    }

    /** @test */
    public function it_can_set_and_retrieve_errors()
    {
        $this->alerts->error('foo');

        $this->assertSame('foo', head($this->alerts->get())->message);
        $this->assertSame('error', head($this->alerts->get())->type);
    }
}
