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
 * @version    4.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use Mockery as m;
use Cartalyst\Alerts\Alerts;
use Cartalyst\Alerts\Message;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\MessageBag;
use Cartalyst\Alerts\Notifiers\Notifier;
use Cartalyst\Alerts\Notifiers\NotifierInterface;

class AlertsTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->alerts = new Alerts();

        $this->alerts->setDefaultNotifier('flash');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_add_and_retrieve_notifiers()
    {
        $notifier1 = m::mock(NotifierInterface::class);
        $notifier1->shouldReceive('get')
            ->once()
            ->andReturn([$message = m::mock(Message::class)])
        ;

        $notifier1->shouldReceive('getName')->once()->andReturn('flash');

        $notifier2 = m::mock(NotifierInterface::class);
        $notifier2->shouldReceive('getName')->once()->andReturn('view');

        $this->alerts->addNotifier($notifier1);
        $this->alerts->addNotifier($notifier2);

        $this->alerts->removeNotifier('view');

        $this->assertSame(['flash' => $notifier1], $this->alerts->getNotifiers());

        $this->assertSame($message, head($this->alerts->get()));
    }

    /** @test */
    public function it_can_retrieve_the_default_notifier_key()
    {
        $this->assertSame('flash', $this->alerts->getDefaultNotifier());
    }

    /** @test */
    public function it_can_retrieve_all_alerts_except_a_specific_type()
    {
        $notifier = m::mock(NotifierInterface::class);

        $notifier->shouldReceive('get')
            ->once()
            ->andReturn([$message = new Message('foo', 'error', 'default')])
        ;

        $notifier->shouldReceive('getName')->once()->andReturn('flash');

        $this->alerts->addNotifier($notifier);

        $this->assertEmpty($this->alerts->whereNotArea('default')->get());
    }

    /** @test */
    public function it_can_retrieve_all_alerts_of_areas()
    {
        $alerts = [
            new Message('foo', 'error', 'header'),
            new Message('foo', 'warning', 'footer'),
        ];

        $notifier = m::mock(NotifierInterface::class);

        $notifier->shouldReceive('get')->andReturn($alerts);

        $notifier->shouldReceive('getName')->once()->andReturn('flash');

        $this->alerts->addNotifier($notifier);

        $this->assertSame($alerts[0], head($this->alerts->whereArea('header')->get()));
        $this->assertSame($alerts[1], head($this->alerts->whereArea('footer')->get()));
    }

    /** @test */
    public function it_can_retrieve_all_alerts_of_types()
    {
        $alerts = [
            new Message('foo', 'error', 'default'),
            new Message('foo', 'warning', 'default'),
        ];

        $notifier = m::mock(NotifierInterface::class);

        $notifier->shouldReceive('get')->once()->andReturn($alerts);

        $notifier->shouldReceive('getName')->once()->andReturn('flash');

        $this->alerts->addNotifier($notifier);

        $this->assertSame($alerts, $this->alerts->whereType(['error', 'warning'])->get());
    }

    /** @test */
    public function it_can_retrieve_all_alerts_of_areas_and_types()
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

        $notifier = m::mock(NotifierInterface::class);

        $notifier->shouldReceive('get')->andReturn($alerts);

        $notifier->shouldReceive('getName')->once()->andReturn('flash');

        $this->alerts->addNotifier($notifier);

        // Header alerts
        $this->assertSame($headerAlerts, array_values($this->alerts->whereArea('header')->get()));

        $this->assertSame($headerAlerts[0], head($this->alerts->whereArea('header')->whereType(['error'])->get()));
        $this->assertSame($headerAlerts[1], head($this->alerts->whereArea('header')->whereType(['warning'])->get()));

        $this->assertSame([$headerAlerts[0], $footerAlerts[0]], array_values($this->alerts->whereType('error')->get()));

        // Footer alerts
        $this->assertSame($footerAlerts, array_values($this->alerts->whereArea('footer')->get()));

        $this->assertSame($footerAlerts[0], head($this->alerts->whereArea('footer')->whereType(['error'])->get()));
        $this->assertSame($footerAlerts[1], head($this->alerts->whereArea('footer')->whereType(['warning'])->get()));

        $this->assertSame([$headerAlerts[1], $footerAlerts[1]], array_values($this->alerts->whereType('warning')->get()));
    }

    /** @test */
    public function it_can_retrieve_alerts_except_areas_and_types()
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

        $notifier = m::mock(NotifierInterface::class);

        $notifier->shouldReceive('get')->andReturn($alerts);

        $notifier->shouldReceive('getName')->once()->andReturn('flash');

        $this->alerts->addNotifier($notifier);

        $this->assertSame($footerAlerts, array_values($this->alerts->whereNotArea('header')->get()));

        $this->assertSame($footerAlerts[0], head($this->alerts->whereNotArea('header')->whereNotType('warning')->get()));
        $this->assertSame($footerAlerts[1], head($this->alerts->whereNotArea('header')->whereNotType('error')->get()));

        $this->assertSame([$headerAlerts[1], $footerAlerts[1]], array_values($this->alerts->whereNotType('error')->get()));

        $this->assertSame($headerAlerts, array_values($this->alerts->whereNotArea('footer')->get()));

        $this->assertSame($headerAlerts[0], head($this->alerts->whereNotArea('footer')->whereNotType('warning')->get()));
        $this->assertSame($headerAlerts[1], head($this->alerts->whereNotArea('footer')->whereNotType('error')->get()));

        $this->assertSame([$headerAlerts[1], $footerAlerts[1]], array_values($this->alerts->whereNotType('error')->get()));
    }

    /** @test */
    public function it_can_retrieve_special_area_methods_using_dynamic_on_methods()
    {
        $notifier = new Notifier('flash');

        $this->alerts->addNotifier($notifier);

        $messageBag = new MessageBag(['foo' => 'bar']);

        $this->alerts->error($messageBag, 'form');

        $this->assertSame('overridden message', $this->alerts->onForm('foo', 'overridden message'));

        $this->assertNull($this->alerts->onForm('bar'));
    }

    /** @test */
    public function it_can_retrieve_notifier()
    {
        $notifier = m::mock(NotifierInterface::class);

        $notifier->shouldReceive('getName')->once()->andReturn('foo');

        $this->alerts->addNotifier($notifier);

        $this->assertSame($notifier, $this->alerts->notifier('foo'));
        $this->assertSame($notifier, $this->alerts->foo());
    }
}
