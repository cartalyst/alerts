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
 * @version    2.0.2
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\MessageBag;
use Cartalyst\Alerts\Notifiers\Notifier;

class NotifierTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->notifier = new Notifier('flash');
    }

    /** @test */
    public function it_can_alert_and_retrieve_all()
    {
        $this->notifier->alert('Error Message', 'error');

        $this->assertCount(1, $this->notifier->get());

        $this->assertSame('Error Message', $this->notifier->get()[0]->message);
    }

    /** @test */
    public function it_can_retrieve_notifier_name()
    {
        $this->assertSame('flash', $this->notifier->getName());
    }

    /** @test */
    public function it_can_set_unknown_types_of_alerts()
    {
        $this->notifier->foo('bar');

        $this->assertSame('foo', $this->notifier->get()[0]->type);
    }

    /** @test */
    public function it_can_handle_message_bags()
    {
        $bag = new MessageBag(['foo' => 'bar']);

        $this->notifier->error($bag);

        $this->assertSame('error', $this->notifier->get()[0]->type);
        $this->assertSame('bar', $this->notifier->get()[0]->message);
    }
}
