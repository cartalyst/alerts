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
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use Mockery as m;
use Cartalyst\Alerts\Alerts;
use Cartalyst\Alerts\Message;
use Cartalyst\Alerts\Notifier;
use PHPUnit_Framework_TestCase;
use Illuminate\Support\MessageBag;

class AlertsTest extends PHPUnit_Framework_TestCase
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

        $this->alerts = new Alerts();
    }

    /** @test */
    public function it_can_add_and_retrieve_notifiers()
    {
        $notifier1 = m::mock('Cartalyst\Alerts\NotifierInterface');
        $notifier1->shouldReceive('all')
            ->once()
            ->andReturn([$message = m::mock('Cartalyst\Alerts\Message')]);

        $notifier2 = m::mock('Cartalyst\Alerts\NotifierInterface');

        $this->alerts->addNotifier('default', $notifier1);
        $this->alerts->addNotifier('view', $notifier2);

        $this->alerts->removeNotifier('view');

        $this->assertSame($message, head($this->alerts->all()));
    }

    /** @test */
    public function it_can_retrieve_all_alerts_except_a_specific_type()
    {
        $notifier = m::mock('Cartalyst\Alerts\NotifierInterface');
        $notifier->shouldReceive('all')
            ->once()
            ->andReturn([$message = new Message('foo', 'error', 'default')]);

        $this->alerts->addNotifier('default', $notifier);

        $this->assertEmpty($this->alerts->except('default'));
    }

    /** @test */
    public function it_can_retrieve_all_alerts_of_areas()
    {
        $alerts = [
            new Message('foo', 'error', 'header'),
            new Message('foo', 'warning', 'footer'),
        ];

        $notifier = m::mock('Cartalyst\Alerts\NotifierInterface');
        $notifier->shouldReceive('all')
            ->andReturn($alerts);

        $this->alerts->addNotifier('default', $notifier);

        $this->assertEquals($alerts[0], head($this->alerts->all('header')));
        $this->assertEquals($alerts[1], head($this->alerts->all('footer')));
    }

    /** @test */
    public function it_can_retrieve_all_alerts_of_types()
    {
        $alerts = [
            new Message('foo', 'error', 'default'),
            new Message('foo', 'warning', 'default'),
        ];

        $notifier = m::mock('Cartalyst\Alerts\NotifierInterface');
        $notifier->shouldReceive('all')
            ->once()
            ->andReturn($alerts);

        $this->alerts->addNotifier('default', $notifier);

        $this->assertSame($alerts, $this->alerts->all(null, ['error', 'warning']));
    }

    /** @test */
    public function it_can_retrieve_alerts_of_areas_and_types()
    {
        $headerAlerts = [
            new Message('header error', 'error', 'header'),
            new Message('header warning', 'warning', 'header'),
        ];

        $footerAlerts = [
            new Message('footer error', 'error', 'footer'),
            new Message('footer warning', 'warning', 'footer'),
        ];

        $alerts = array_merge($headerAlerts, $footerAlerts);

        $notifier = m::mock('Cartalyst\Alerts\NotifierInterface');
        $notifier->shouldReceive('all')
            ->andReturn($alerts);

        $this->alerts->addNotifier('default', $notifier);

        // Header alerts
        $this->assertEquals($headerAlerts, array_values($this->alerts->all('header')));

        $this->assertEquals($headerAlerts[0], head($this->alerts->all('header', ['error'])));
        $this->assertEquals($headerAlerts[1], head($this->alerts->all('header', ['warning'])));

        $this->assertEquals([$headerAlerts[0], $footerAlerts[0]], array_values($this->alerts->all(null, 'error')));

        // Footer alerts
        $this->assertEquals($footerAlerts, array_values($this->alerts->all('footer')));

        $this->assertEquals($footerAlerts[0], head($this->alerts->all('footer', ['error'])));
        $this->assertEquals($footerAlerts[1], head($this->alerts->all('footer', ['warning'])));

        $this->assertEquals([$headerAlerts[1], $footerAlerts[1]], array_values($this->alerts->all(null, 'warning')));
    }

    /** @test */
    public function it_can_retrieve_form_element_errors()
    {
        $notifier = new Notifier();

        $this->alerts->addNotifier('default', $notifier);

        $messageBag = new MessageBag(['foo' => 'bar']);

        $this->alerts->error($messageBag, 'form');

        $this->assertEquals('overridden message', $this->alerts->form('foo', 'overridden message')) ;

        $this->assertNull($this->alerts->form('bar'));
    }

    /** @test */
    public function it_can_retrieve_the_view_notifier()
    {
        $notifier = m::mock('Cartalyst\Alerts\NotifierInterface');

        $this->alerts->addNotifier('view', $notifier);

        $this->assertSame($notifier, $this->alerts->view());
    }
}
